<?php

namespace App\Http\Controllers;

use app\core\Response;
use App\Models\Configuration;
use App\Models\Rol;
use App\Models\App;
use App\Models\RolAccess;
use App\Models\RolApp;
use App\Models\User;
use App\Models\UserRol;
use App\Models\UserLog;
use App\Models\UserApiKey;
use App\Models\UserTmp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

use Laravel\Sanctum\PersonalAccessToken;
use Mailgun\Mailgun;
use Matrix\Exception;

class AuthController extends Controller {

    use Response;

    public function loginValidate(Request $request) {

        $tokenBearer = $request->bearerToken();
        $app = $request->get('app');
        $subToken = $request->get('stoken');

        /*if (empty($app)) {
                return $this->ResponseError('AUTH-DOM14', 'Dominio inválido o sin autorización');
            }*/

        $ssoDomain = env('APP_DOMAIN');
        $token = PersonalAccessToken::where([['token', '=', $tokenBearer], ['tokenable_type', '=', $ssoDomain]])->first();

        if (!empty($token)) {
            $user = User::where('id', $token->tokenable_id)->first();
            define('SSO_USER', $user ?? false);
            $getUserAccess = $this->GetUserAccess();
            $tokenTmp = '';

            if (!empty($app)) {

                // verifica accesos por app
                $userHasAccess = false;
                $appsForUserTmp = $user->apps;
                $appsForUser = [];
                foreach ($appsForUserTmp as $app) {
                    $appsForUser[$app->appId] = true;
                }

                // apps para rol
                $rolUsuario = $user->rolAsignacion;
                $appsForRolTmp = RolApp::where([['rolId', '=', $rolUsuario->rolId]])->get();
                $appsForRol = [];
                foreach ($appsForRolTmp as $app) {
                    $appsForRol[$app->appId] = true;
                }
                $itemList = App::all();
                $response = [];

                foreach ($itemList as $item) {
                    // chequeo que esté en el usuario
                    if (!isset($appsForUser[$item->id])) {
                        // chequeo que esté en el rol
                        if (!isset($appsForRol[$item->id])) continue;
                        continue;
                    }
                    $userHasAccess = true;
                }

                if (!$userHasAccess) {
                    return $this->ResponseError('AUTH-S31', 'No tienes acceso a esta aplicación');
                }

                // si está logueado, creo el token para la nueva app
                $subTokenTmp = PersonalAccessToken::where([['tokenable_id', '=', $user->id], ['tokenable_type', '=', $app], ['token', '=', $subToken]])->first();

                // valido subtoken
                if (empty($subTokenTmp)) {

                    // si inicio sesión, cierro en otros lugares
                    PersonalAccessToken::where([['tokenable_id', '=', $user->id], ['tokenable_type', '=', $app]])->delete();

                    $tokenTmp = random_bytes(20);
                    $tokenTmp = bin2hex($tokenTmp);
                    $userTmpId = md5($user->id);
                    $tokenTmp = "{$user->id}{$tokenTmp}{$userTmpId}";
                    $dateNow = Carbon::now();

                    $subTokenTmp = new PersonalAccessToken();
                    $subTokenTmp->tokenable_type = $app;
                    $subTokenTmp->name = $user->name;
                    $subTokenTmp->tokenable_id = $user->id;
                    $subTokenTmp->token = $tokenTmp;
                    $subTokenTmp->expires_at = $dateNow->addDays(3)->toTimeString();
                    $subTokenTmp->last_used_at = Carbon::now()->toTimeString();
                    $subTokenTmp->created_at = Carbon::now()->toTimeString();
                    $subTokenTmp->updated_at = Carbon::now()->toTimeString();
                    $subTokenTmp->save();
                }
                else {
                    $tokenTmp = $subTokenTmp->token;
                }
            }

            // validar expiración
            $now = Carbon::now();
            $expireAt = Carbon::parse($token->expires_at);

            /*var_dump($now);
            dd($expireAt);*/

            if ($now->gt($expireAt)) {
                $token->delete();
                return $this->ResponseError('AUTH-S21', 'La sesión ha vencido');
            }

            // expiración de pass
            $expirePass = false;
            $passExpiryDays = $user->expiryDays;

            if (!empty($passExpiryDays)) {
                $dateNow = Carbon::now();
                $passExpiryLastDate = (!empty($user->expiryLastDate) ? $user->expiryLastDate : $user->created_at);
                $passExpiryLastDate = Carbon::parse($passExpiryLastDate);
                $expiryDate = $passExpiryLastDate->addDays($passExpiryDays);

                // si la fecha de expiración es mayor a hoy
                if ($dateNow->gt($expiryDate)) {
                    $expirePass = true;
                }
            }

            return $this->ResponseSuccess('Usuario logueado', [
                'logged' => 1,
                'token' => $user->token,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->nombreUsuario,
                'm' => $getUserAccess,
                'st' => $tokenTmp,
                'ep' => $expirePass,
            ]);
        }
        else {
            return $this->ResponseError('AUTH-TKNFA', 'Token inválido');
        }
    }
    public function loginUser(Request $request) {
        try {

            $usuario = $request->get('nombreUsuario');
            $password = $request->get('password');
            $app = $request->get('app');

            // para desarrollo
            if ($app === 'localhost' || empty($app)) {
                $app = env('APP_DOMAIN');
            }

            $user = User::where('nombreUsuario', $usuario)->first();

            if (!empty($user)) {

                $ipFrom = $_SERVER['REMOTE_ADDR'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? null);

                $detectDevice = new \Detection\MobileDetect;
                $userAgent = $detectDevice->getUserAgent();

                if (!Hash::check($password, $user->password)) {
                    return $this->ResponseError('AUTH-I60F', 'El usuario no existe o la contraseña es incorrecta');
                }

                // obtengo si el usuario tiene acceso al app
                // traigo el app
                if ($app !== env('APP_DOMAIN')) {
                    $appTmp = App::where('domain', $app)->first();

                    if (empty($appTmp)) {
                        return $this->ResponseError('AUTH-APP12', 'Dominio de aplicación inválida');
                    }

                    // verifica accesos por app
                    $userHasAccess = false;
                    $appsForUserTmp = $user->apps;
                    $appsForUser = [];
                    foreach ($appsForUserTmp as $app) {
                        $appsForUser[$app->appId] = true;
                    }

                    // apps para rol
                    $rolUsuario = $user->rolAsignacion;
                    $appsForRolTmp = RolApp::where([['rolId', '=', $rolUsuario->rolId]])->get();
                    $appsForRol = [];
                    foreach ($appsForRolTmp as $app) {
                        $appsForRol[$app->appId] = true;
                    }
                    $itemList = App::all();
                    $response = [];

                    foreach ($itemList as $item) {
                        // chequeo que esté en el usuario
                        if (!isset($appsForUser[$item->id])) {
                            // chequeo que esté en el rol
                            if (!isset($appsForRol[$item->id])) continue;
                            continue;
                        }
                        $userHasAccess = true;
                    }

                    if (!$userHasAccess) {
                        return $this->ResponseError('AUTH-S31', 'No tienes acceso a esta aplicación');
                    }
                }

                // creo el token
                $tokenTmp = random_bytes(20);
                $tokenTmp = bin2hex($tokenTmp);
                $userTmpId = md5($user->id);
                $tokenTmp = "{$user->id}{$tokenTmp}{$userTmpId}";

                $dateNow = Carbon::now();
                $token = PersonalAccessToken::where([['tokenable_id', '=', $user->id], ['tokenable_type', '=', $app]])->first();

                if (empty($token)) {
                    $token = new PersonalAccessToken();
                }
                $token->tokenable_type = $app;
                $token->name = $user->name;
                $token->tokenable_id = $user->id;
                $token->token = $tokenTmp;
                $token->expires_at = $dateNow->addDays(2)->toDateTimeString();
                $token->last_used_at = Carbon::now()->toDateTimeString();
                $token->created_at = Carbon::now()->toDateTimeString();
                $token->updated_at = Carbon::now()->toDateTimeString();
                $token->save();

                // guardo log
                $userLog = new UserLog();
                $userLog->userId = $user->id;
                $userLog->ipFrom = $ipFrom;
                $userLog->userAgent = $userAgent;
                $userLog->save();

                return $this->ResponseSuccess('Ok', [
                    'logged' => 1,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->nombreUsuario,
                    'token' => $tokenTmp,
                    'app' => $app,
                ]);
            }
            else {
                return $this->ResponseError('AUTH-UI60F', 'El usuario no existe o la contraseña es incorrecta');
            }

        } catch (\Throwable $th) {
            /*var_dump($th->getMessage());
            die();*/
            return $this->ResponseError('AUTH-AF60F', 'Error al iniciar sesión');
        }
    }
    public function expirarPasswords(Request $request) {

        dd('asdfsd');

        $user = User::where('resetPassword', $request->token)->first();

        return $this->ResponseSuccess('Se ha cambiado tu contraseña');
    }
    public function loginClose(Request $request) {

        $token = PersonalAccessToken::where([['tokenable_id', '=', SSO_USER->id]]);

        if ($token->delete()) {
            return $this->ResponseSuccess('Ok');
        }
        else {
            return $this->ResponseError('LG-54', 'Error al cerrar sesión');
        }
    }
    public function CheckAccess($accessToCheck = []) {

        //return true;

        $hasAccess = true;
        $accessListUser = $this->GetUserAccess();
        foreach ($accessToCheck as $access) {
            if (!isset($accessListUser[$access])) {
                $hasAccess = false;
            }
        }
        //return true;
        return $hasAccess;
    }
    public function NoAccess() {
        return $this->ResponseError('AUTH-001', 'Usuario sin acceso al área solicitada');
    }
    public function GetUserAccess() {
        if (!defined('SSO_USER') || !SSO_USER) return [];
        $user = SSO_USER;
        $rolUser = $user->rolAsignacion->rol ?? 0;
        $accessTMP = $this->LoadUserAccess($rolUser->id ?? 0);
        return $accessTMP['data'];
    }
    public function GetUserList() {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['users/admin'])) return $AC->NoAccess();

        $users = User::whereNotNull('email_verified_at')->with('rolAsignacion')->get();

        $usersTMp = [];

        if (!empty($users)) {

            foreach ($users as $user) {

                if (empty($user->rolAsignacion)) {
                    $user->rolUsuario = 'Sin rol';
                }
                else {
                    $user->rolUsuario = $user->rolAsignacion->rol->name ?? '';
                }
                $user->estado = ($user->active) ? 'Activo' : 'Desactivado';

                $user->makeHidden(['rolAsignacion', 'email_verified_at', 'updated_at']);

                $usersTMp[] = $user;
            }

            return $this->ResponseSuccess('Información obtenida con éxito', $usersTMp);
        }
        else {
            return $this->ResponseError('Error al listar usuarios');
        }
    }
    public function LoadUser($userid) {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['users/admin'])) return $AC->NoAccess();

        $user = User::where([['id', '=', $userid]])->with('rolAsignacion', 'log')->first();

        if (!empty($user)) {
            $user->rolUsuario = $user->rolAsignacion->rolId ?? 0;

            // proceso el log
            $logArr = [];
            foreach ($user->log as $log) {
                $log->date = Carbon::parse($log->created_at)->format('d-m-Y H:i:s');
                $logArr[] = $log;
            }
            $user->logs = $logArr;

            $user->makeHidden(['rolAsignacion', 'log', 'email_verified_at', 'updated_at', 'apps', 'apiKeys']);

            return $this->ResponseSuccess('Ok', $user);
        }
        else {
            return $this->ResponseError('USR-8548', 'Usuario no válido');
        }
    }
    public function SaveUser(Request $request) {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['users/admin'])) return $AC->NoAccess();

        $id = $request->get('id');

        $nombreUsuario = $request->get('nombreUsuario');
        $nombreUsuario = trim(strip_tags($nombreUsuario));

        $name = $request->get('nombre');
        $email = $request->get('correoElectronico');
        $password = $request->get('password');
        $rol = $request->get('rolUsuario');
        $active = $request->get('active');
        $expiryDays = $request->get('expiryDays');
        $telefono = $request->get('telefono');
        $telefono = str_replace('-', '', $telefono);
        $telefono = str_replace(' ', '', $telefono);


        $corporativo = $request->get('corporativo');
        $changePassword = $request->get('changePassword');

        $sendCredentials = $request->get('sendCredentials');
        $sendCredentialsEmail = $request->get('sendCredentialsEmail');
        $sendCredentialsSMS = $request->get('sendCredentialsSMS');
        $sendCredentialsWhatsapp = $request->get('sendCredentialsWhatsapp');

        $appList = $request->get('appList');

        // validación de campos
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->ResponseError('US-EM14', 'Correo electrónico inválido');
        }

        $role = Rol::where([['id', '=', $rol]])->first();

        if (empty($role)) {
            return $this->ResponseError('AUTH-RUE93', 'El rol no existe o es inválido');
        }

        //dd($id);

        if (empty($id)) {

            $password = (!empty($password) ? $password : $this->randomPassword());

            $user = new User();
            $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
            $user->token = md5(uniqid()).uniqid();
            $user->password = $password;
        }
        else {
            $user = User::where('id', $id)->first();
        }

        if ($user->nombreUsuario !== $nombreUsuario) {
            // verifico el correo electrónico
            $userTmp = User::where('nombreUsuario', $nombreUsuario)->first();
            if (!empty($userTmp)) {
                return $this->ResponseError('AUTH-UE934', 'El nombre de usuario ya se encuentra en uso');
            }
        }

        // verifico email duplicado
        $userTmp = User::where([['email', '=', $email], ['id', '<>', $id]])->first();
        if (!empty($userTmp)) {
            return $this->ResponseError('AUTH-UE934', 'El correo electrónico ya se encuentra configurado en otro usuario');
        }

        // verifico el corporativo
        if (!empty($corporativo)) {
            $userTmp = User::where([['corporativo', '=', $corporativo], ['id', '<>', $id]])->first();
            if (!empty($userTmp)) {
                return $this->ResponseError('AUTH-UE934', 'El corporativo ya se encuentra configurado en otro usuario');
            }
        }

        // traigo la configuración
        $configH = new ConfigController();

        if ($changePassword) {
            // Validar password
            $password = strip_tags($password);
            $passConfig = $configH->GetConfig('passwordSecurity');

            $passwordOk = true;
            if (!empty($passConfig->longitudPass)) {
                if (strlen($password) < $passConfig->longitudPass) $passwordOk = false;
            }
            if (!empty($passConfig->letrasPass)) {
                if(!preg_match('/[A-Z]/', $password)) $passwordOk = false;
            }
            if (!empty($passConfig->numerosPass)) {
                if(!preg_match('/[0-9]/', $password)) $passwordOk = false;
            }
            if (!empty($passConfig->caracteresPass)) {
                if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) $passwordOk = false;
            }

            if (!$passwordOk) {
                return $this->ResponseError('AUTH-FL9AF', 'La contraseña no cumple con los parámetros establecidos');
            }
            $user->password = Hash::make($password);
        }

        $user->nombreUsuario = $nombreUsuario;
        $user->name = strip_tags($name);
        $user->email = strip_tags($email);
        $user->telefono = strip_tags($telefono);
        $user->corporativo = strip_tags($corporativo);
        $user->active = intval($active);
        $user->expiryDays = intval($expiryDays);
        $user->save();

        $userRole = UserRol::firstOrNew(['userId' => $user->id]);
        $userRole->rolId = $role->id;
        $userRole->save();

        if (!empty($user)) {

            // envio de credenciales
            if ($sendCredentialsEmail) {

                $config = $configH->GetConfig('mailgunNotifyConfig');
                $mg = Mailgun::create($config->apiKey ?? ''); // For US servers

                // reemplazo plantilla
                $asunto = $configH->GetConfig('templateHtmlAsunto');
                $templateHtml = $configH->GetConfig('userCreateTemplateHtml');
                $templateHtml = str_replace('::URL_LOGIN::', env('APP_URL'), $templateHtml);
                $templateHtml = str_replace('::PASSWORD::', $password ?? '*****', $templateHtml);
                $templateHtml = str_replace('::USERNAME::', $nombreUsuario, $templateHtml);
                $templateHtml = str_replace('::NAME::', $user->name, $templateHtml);
                $templateHtml = str_replace('::CORREO::', $user->email, $templateHtml);
                $templateHtml = str_replace('::TELEFONO::', $user->telefono, $templateHtml);
                $templateHtml = str_replace('::CORPORATIVO::', $user->corporativo, $templateHtml);

                try {
                    $mg->messages()->send($config->domain ?? '', [
                        'from' => $config->from ?? '',
                        'to' => $email,
                        'subject' => $asunto,
                        'html' => $templateHtml
                    ]);
                } catch (Exception $e) {
                    return $this->ResponseError('AUTH-RA94', 'Error al enviar notificación, verifique el correo o la configuración del sistema');
                }
            }

            /*
             Notificación para recibir la primera contraseña
            curl --location --request POST 'https://api-india.yalochat.com/notifications/api/v1/accounts/corporacion-bi/bots/seguros_el_roble/notifications' --header 'Authorization: Bearer PON_TU_CLAVE_API_AQUÍ' --header 'Content-Type: application/json' --data '{"type":"bienvenida-sso","users":[{"priority":"<priority>","phone":"+<phone>","params":{"nombre_asegurado":"<nombre_asegurado>","usuario":"<usuario>","Contrasena":"<Contrasena>"}}]}'


            Notificación para reinicio de contraseña

            curl --location --request POST 'https://api-india.yalochat.com/notifications/api/v1/accounts/corporacion-bi/bots/seguros_el_roble/notifications' --header 'Authorization: Bearer PON_TU_CLAVE_API_AQUÍ' --header 'Content-Type: application/json' --data '{"type":"cambio-contrasena-sso","users":[{"priority":"<priority>","phone":"+<phone>","params":{"nombre_asegurado":"<nombre_asegurado>","usuario":"<usuario>","Contrasena":"<Contrasena>"}}]}'
             * */

            //dd($sendCredentialsWhatsapp);

            if ($sendCredentialsWhatsapp) {

                //dd($sendCredentialsWhatsapp);

                if (empty($telefono)) {
                    return $this->ResponseError('AUTH-RL9AF', 'Teléfono inválido para envío de Whatsapp');
                }

                if (empty($id)) {
                    $whatsappCredentialsSend = $configH->GetConfig('whatsappCredentialsSend', true);
                    $whatsappCredentialsSendToken = $configH->GetConfig('whatsappCredentialsSendToken');
                    $whatsappCredentialsSendUrl = $configH->GetConfig('whatsappCredentialsSendUrl');
                }
                else {
                    $whatsappCredentialsSend = $configH->GetConfig('whatsappCredentialsSendEdit', true);
                    $whatsappCredentialsSendToken = $configH->GetConfig('whatsappCredentialsSendEditToken');
                    $whatsappCredentialsSendUrl = $configH->GetConfig('whatsappCredentialsSendEditUrl');
                }


                // reemplazo plantilla
                $whatsappCredentialsSend = str_replace('::URL_LOGIN::', env('APP_URL'), $whatsappCredentialsSend);
                $whatsappCredentialsSend = str_replace('::USERNAME::', $nombreUsuario, $whatsappCredentialsSend);
                $whatsappCredentialsSend = str_replace('::NAME::', $user->name, $whatsappCredentialsSend);
                $whatsappCredentialsSend = str_replace('::CORREO::', $user->email, $whatsappCredentialsSend);
                $whatsappCredentialsSend = str_replace('::TELEFONO::', $user->telefono, $whatsappCredentialsSend);
                $whatsappCredentialsSend = str_replace('::CORPORATIVO::', $user->corporativo, $whatsappCredentialsSend);

                if (!empty($password)) {
                    $whatsappCredentialsSend = str_replace('::PASSWORD::', $password, $whatsappCredentialsSend);
                }

                //dd($whatsappCredentialsSend);

                $headers = [
                    'Authorization: Bearer ' . $whatsappCredentialsSendToken ?? '',
                    'Content-Type: application/json',
                ];

                //dd($whatsappCredentialsSend);
                //dd($whatsappCredentialsSend);
                //var_dump($headers);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $whatsappCredentialsSendUrl ?? '');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $whatsappCredentialsSend);  //Post Fields
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $server_output = curl_exec($ch);
                $server_output = @json_decode($server_output, true);
                //dd($server_output);
                curl_close($ch);

                if (empty($server_output['success'])) {
                    return $this->ResponseError('AUTH-RL934', 'Error al enviar Whatsapp: ' . ($server_output['reason']['details'][0]['description'] ?? 'Error desconocido'));
                }
            }
            return $this->ResponseSuccess('Usuario guardado con éxito', $user->id);
        }
        else {
            return $this->ResponseError('AUTH-RL934', 'Error al crear rol');
        }
    }
    public function ChangePass(Request $request) {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['mi-usuario/password'])) return $AC->NoAccess();

        $password = $request->get('password');
        $changePassword = $request->get('changePassword');

        if (!defined('SSO_USER') || !SSO_USER) return [];
        $user = SSO_USER;
        $id = $user->id;
        $user = User::where('id', $id)->first();

        if (empty($user)) {
            return $this->ResponseError('AUTH-ZFA0', 'Usuario inválido');
        }

        // traigo la configuración
        $configH = new ConfigController();

        if ($changePassword) {
            // Validar password
            $password = strip_tags($password);
            $passConfig = $configH->GetConfig('passwordSecurity');

            $passwordOk = true;
            if (!empty($passConfig->longitudPass)) {
                if (strlen($password) < $passConfig->longitudPass) $passwordOk = false;
            }
            if (!empty($passConfig->letrasPass)) {
                if(!preg_match('/[A-Z]/', $password)) $passwordOk = false;
            }
            if (!empty($passConfig->numerosPass)) {
                if(!preg_match('/[0-9]/', $password)) $passwordOk = false;
            }
            if (!empty($passConfig->caracteresPass)) {
                if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) $passwordOk = false;
            }

            if (!$passwordOk) {
                return $this->ResponseError('AUTH-FL9AF', 'La contraseña no cumple con los parámetros establecidos');
            }
            $user->password = Hash::make($password);
        }

        if (!empty($user->expiryDays)) {
            $now = Carbon::now();
            $expireDate = $now->addDays($user->expiryDays);
            $expireDate = $expireDate->format('Y-m-d H:i:s');
        }

        $user->expiryLastDate = $expireDate;
        $user->save();

        return $this->ResponseSuccess('Usuario actualizado con éxito', $user->id);
    }

    public function DeleteUser(Request $request) {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['users/admin'])) return $AC->NoAccess();

        $id = $request->get('id');
        try {
            $user = User::find($id);

            if (!empty($user)) {
                $user->active = 0;
                $user->save();
                return $this->ResponseSuccess('Eliminado con éxito', $user->id);
            }
            else {
                return $this->ResponseError('AUTH-UR532', 'Error al eliminar');
            }
        } catch (\Throwable $th) {
            //var_dump($th->getMessage());
            return $this->ResponseError('AUTH-UR530', 'Error al eliminar');
        }
    }
    public function GetRoleList() {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['users/role/admin'])) return $AC->NoAccess();

        $roleList = Rol::all();

        $roles = [];

        foreach ($roleList as $rol) {
            $roles[] = [
                'id' => $rol->id,
                'name' => $rol->name,
            ];
        }

        if (!empty($roleList)) {
            return $this->ResponseSuccess('Ok', $roles);
        }
        else {
            return $this->ResponseError('Error al listar roles');
        }
    }
    public function GetRoleAccessList() {
        return $this->ResponseSuccess('Ok', LgcAccessConfig);
    }
    public function GetRoleDetail($rolId) {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['users/role/admin'])) return $AC->NoAccess();

        $role = Rol::where([['id', '=', $rolId]])->first();

        if (!empty($role)) {

            // traigo accesos
            $accessList = $role->access;
            $access = [];
            foreach ($accessList as $accessTmp) {
                $access[$accessTmp['access']] = true;
            }

            return $this->ResponseSuccess('Ok', [
                'nombre' => $role->name,
                'access' => $access,
            ]);
        }
        else {
            return $this->ResponseError('Rol inválido');
        }
    }
    public function SaveRole(Request $request) {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['users/role/admin'])) return $AC->NoAccess();

        $roleId = $request->get('id');
        $name = $request->get('nombre');
        $access = $request->get('access');

        if (!empty($roleId)) {
            $role = Rol::where([['id', '=', $roleId]])->first();
        }
        else {
            $role = new Rol();
        }

        $role->name = strip_tags($name);
        $role->save();

        if (!empty($role)) {

            // borro los accesos por rol
            RolAccess::where([['rolId', '=', $role->id]])->delete();

            // guardo los accesos
            foreach ($access as $modulo) {
                foreach ($modulo['access'] as $permiso) {
                    if (!empty($permiso['active'])) {
                        $acceso = new RolAccess();
                        $acceso->rolId = $role->id;
                        $acceso->access = $permiso['slug'];
                        $acceso->save();
                    }
                }
            }

            return $this->ResponseSuccess('Guardado con éxito', $role->id);
        }
        else {
            return $this->ResponseError('AUTH-RL934', 'Error al crear rol');
        }
    }
    public function DeleteRole(Request $request) {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['users/role/admin'])) return $AC->NoAccess();

        $id = $request->get('id');
        try {
            $role = Rol::find($id);

            if (!empty($role)) {
                $role->delete();
                return $this->ResponseSuccess('Eliminado con éxito', $role->id);
            }
            else {
                return $this->ResponseError('AUTH-R5321', 'Error al eliminar');
            }
        } catch (\Throwable $th) {
            return $this->ResponseError('AUTH-R5302', 'Error al eliminar');
        }
    }
    public function LoadUserAccess($roleid) {

        $role = Rol::where([['id', '=', $roleid]])->select('id', 'name')->first();

        if (!empty($role)) {
            $role = Rol::where([['id', '=', $roleid]])->first();
        }
        else {
            return $this->ResponseError('ERRO-5148', 'El rol no existe');
        }

        $roles = [];
        $roles['rol'] = $role ?? [];
        $roles['access'] = LgcAccessConfig;

        $permisions = [];
        if (!empty($role)) {
            $permisions = $role->access;
            $permisions = $permisions->toArray();
        }

        $accessList = [];

        try {
            foreach ($roles['access'] as $keyModule => $access) {

                /*var_dump($keyModule);
                dd($access);*/

                foreach ($access['access'] as $accessKey => $accessTmp) {

                    foreach ($permisions as $permision) {

                        if (empty($roles['access'][$keyModule]['access'][$accessKey]['status'])) {

                            if ($permision['access'] == $accessTmp['slug']) {
                                $accessList[$access['module']] = true;
                                $accessList[$permision['access']] = true;
                            }
                        }
                    }
                }
            }

            return $this->ResponseSuccess('Ok', $accessList, false);
        } catch (\Mockery\Exception $exception) {
            return $this->ResponseError('ERRAU-547', 'Error al cargar', $roles);
        }
    }
    private function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyz()*/=@ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890()*/=@123456789';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
