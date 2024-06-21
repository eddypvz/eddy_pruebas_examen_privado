<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserApiKey;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ssologin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $tokenBearer = $request->bearerToken();
        $app = env('APP_DOMAIN');
        $isAPI = false;
        $user = null;

        if (strpos($tokenBearer, ':') > 0) {
            $tokenTmp = explode(':', $tokenBearer);
            $apiKeyTmp = UserApiKey::where([['apiKey', '=', $tokenTmp[0]], ['secretApiKey', '=', $tokenTmp[1]], ['activo', '=', 1]])->first();

            if (!empty($apiKeyTmp)) {
                $user = User::where('id', $apiKeyTmp->userId)->first();
            }
        }
        else {
            $token = PersonalAccessToken::where([['token', '=', $tokenBearer], ['tokenable_type', '=', $app]])->first();

            if (!empty($token)) {
                $user = User::where('id', $token->tokenable_id)->first();
            }
        }

        define('SSO_USER', $user);
        return $next($request);
    }
}
