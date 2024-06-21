<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CognitoController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ConfigController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post('auth/register', [AuthController::class, 'createUser']); // el registro estará deshabilitado por default por ser sistemas internos
/*
*/
Route::post('auth/validate-login', [AuthController::class, 'loginValidate']);
Route::post('auth/login', [AuthController::class, 'loginUser']);
Route::post('auth/exp-proccess', [AuthController::class, 'expirarPasswords']);

// PROTEGIDAS
Route::middleware('sso')->group(function () {

    Route::post('auth/logout', [AuthController::class, 'loginClose']);

    // Accesos y menú
    Route::get('users/list', [AuthController::class, 'GetUserList']);
    Route::get('users/load/access/{roleid}', [AuthController::class, 'LoadUserAccess']);

    // usuarios
    Route::get('users/load/user/{userid}', [AuthController::class, 'LoadUser']);
    Route::post('users/save-user', [AuthController::class, 'SaveUser']);
    Route::post('users/change-password', [AuthController::class, 'ChangePass']);
    Route::post('users/user/delete', [AuthController::class, 'DeleteUser']);

    // roles
    Route::get('users/role/access/list', [AuthController::class, 'GetRoleAccessList']);
    Route::get('users/role/list', [AuthController::class, 'GetRoleList']);
    Route::get('users/role/load/{rolId}', [AuthController::class, 'GetRoleDetail']);
    Route::post('users/save-role', [AuthController::class, 'SaveRole']);
    Route::post('users/role/delete', [AuthController::class, 'DeleteRole']);

    // configuración
    Route::get('configuration/load', [ConfigController::class, 'Load']);
    Route::get('configuration/load/pass', [ConfigController::class, 'LoadPassConfig']);
    Route::post('configuration/save', [ConfigController::class, 'SaveConfig']);
});
