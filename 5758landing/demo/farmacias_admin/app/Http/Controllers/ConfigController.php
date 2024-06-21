<?php

namespace App\Http\Controllers;

use app\core\Response;
use App\Models\Configuration;
use App\Models\App;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller {

    use Response;

    private function token($length = 50) {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }

    public function GetList() {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['configuration'])) return $AC->NoAccess();

        $itemList = App::all();

        $response = [];

        foreach ($itemList as $item) {
            $response[] = [
                'id' => $item->id,
                'nombre' => $item->nombre,
                'urlLogin' => $item->urlLogin,
                'logo' => $item->logo,
            ];
        }

        if (!empty($itemList)) {
            return $this->ResponseSuccess('Ok', $response);
        }
        else {
            return $this->ResponseError('Error al obtener aplicaciones');
        }
    }

    public function Load() {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['configuration'])) return $AC->NoAccess();

        $items = Configuration::all();

        $config = [];
        foreach ($items as $item) {
            $config[$item->slug] = ($item->typeRow === 'json') ? @json_decode($item->dataText) : $item->dataText;
        }

        if (!empty($config)) {
            return $this->ResponseSuccess('Ok', $config);
        }
        else {
            return $this->ResponseError('Error al obtener configuración');
        }
    }



    public function LoadPassConfig() {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['mi-usuario/password'])) return $AC->NoAccess();

        $items = Configuration::where('slug', 'passwordSecurity')->get();

        $config = [];
        foreach ($items as $item) {
            $config[$item->slug] = ($item->typeRow === 'json') ? @json_decode($item->dataText) : $item->dataText;
        }

        if (!empty($config)) {
            return $this->ResponseSuccess('Ok', $config);
        }
        else {
            return $this->ResponseError('Error al obtener configuración');
        }
    }

    public function GetConfig($slug, $disableJsonDecode = false) {

        $items = Configuration::all();

        $config = [];
        $config['whatsappCredentialsSend'] = [];
        $config['whatsappPasswordForgot'] = [];

        foreach ($items as $item) {
            $config[$item->slug] = ($item->typeRow === 'json' && !$disableJsonDecode) ? @json_decode($item->dataText) : $item->dataText;
        }

        if (!empty($config[$slug])) {
            return $config[$slug];
        }
        else {
            return '';
        }
    }

    public function SaveConfig(Request $request) {

        $AC = new AuthController();
        if (!$AC->CheckAccess(['configuration'])) return $AC->NoAccess();

        $mailgun = $request->get('mailgun');
        $whatsappCredentialsSend = $request->get('whatsappCredentialsSend');
        $whatsappCredentialsSendToken = $request->get('whatsappCredentialsSendToken');
        $whatsappCredentialsSendUrl = $request->get('whatsappCredentialsSendUrl');

        $whatsappPasswordForgot = $request->get('whatsappPasswordForgot');
        $whatsappPasswordForgotToken = $request->get('whatsappPasswordForgotToken');
        $whatsappPasswordForgotUrl = $request->get('whatsappPasswordForgotUrl');

        $whatsappCredentialsSendEdit = $request->get('whatsappCredentialsSendEdit');
        $whatsappCredentialsSendEditToken = $request->get('whatsappCredentialsSendEditToken');
        $whatsappCredentialsSendEditUrl = $request->get('whatsappCredentialsSendEditUrl');

        $templateHtmlAsunto = $request->get('templateHtmlAsunto');
        $templateHtml = $request->get('templateHtml');
        $userResetTemplateHtml = $request->get('userResetTemplateHtml');
        $userResetTemplateHtmlAsunto = $request->get('userResetTemplateHtmlAsunto');
        $passwordSecurity = $request->get('passwordSecurity');

        Configuration::updateOrCreate(['slug' => 'whatsappCredentialsSend'], ['dataText'  => (!empty($whatsappCredentialsSend) && is_array($whatsappCredentialsSend) ? @json_encode($whatsappCredentialsSend) : trim($whatsappCredentialsSend ?? '') ), 'typeRow' => 'json']);
        Configuration::updateOrCreate(['slug' => 'whatsappCredentialsSendToken'], ['dataText'  => $whatsappCredentialsSendToken]);
        Configuration::updateOrCreate(['slug' => 'whatsappCredentialsSendUrl'], ['dataText'  => $whatsappCredentialsSendUrl]);

        Configuration::updateOrCreate(['slug' => 'whatsappPasswordForgot'], ['dataText'  => (!empty($whatsappPasswordForgot) && is_array($whatsappPasswordForgot) ? @json_encode($whatsappPasswordForgot) : trim($whatsappPasswordForgot ?? '') ), 'typeRow' => 'json']);
        Configuration::updateOrCreate(['slug' => 'whatsappPasswordForgotToken'], ['dataText'  => $whatsappPasswordForgotToken]);
        Configuration::updateOrCreate(['slug' => 'whatsappPasswordForgotUrl'], ['dataText'  => $whatsappPasswordForgotUrl]);

        Configuration::updateOrCreate(['slug' => 'whatsappCredentialsSendEdit'], ['dataText'  => (!empty($whatsappCredentialsSendEdit) && is_array($whatsappCredentialsSendEdit) ? @json_encode($whatsappCredentialsSendEdit) : trim($whatsappCredentialsSendEdit ?? '') ), 'typeRow' => 'json']);
        Configuration::updateOrCreate(['slug' => 'whatsappCredentialsSendEditToken'], ['dataText'  => $whatsappCredentialsSendEditToken]);
        Configuration::updateOrCreate(['slug' => 'whatsappCredentialsSendEditUrl'], ['dataText'  => $whatsappCredentialsSendEditUrl]);

        Configuration::updateOrCreate(['slug' => 'mailgunNotifyConfig'], ['dataText'  => @json_encode($mailgun ?? []), 'typeRow' => 'json']);
        Configuration::updateOrCreate(['slug' => 'templateHtmlAsunto'], ['dataText'  => $templateHtmlAsunto]);
        Configuration::updateOrCreate(['slug' => 'userCreateTemplateHtml'], ['dataText'  => $templateHtml]);
        Configuration::updateOrCreate(['slug' => 'userResetTemplateHtml'], ['dataText'  => $userResetTemplateHtml]);
        Configuration::updateOrCreate(['slug' => 'userResetTemplateHtmlAsunto'], ['dataText'  => $userResetTemplateHtmlAsunto]);
        Configuration::updateOrCreate(['slug' => 'passwordSecurity'], ['dataText'  => @json_encode($passwordSecurity ?? []), 'typeRow' => 'json']);

        return $this->ResponseSuccess('Configuración actualizada con éxito');
    }
}
