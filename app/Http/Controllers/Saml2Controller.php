<?php

namespace App\Http\Controllers;

use Aacotroneo\Saml2\Saml2Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Saml2Controller extends Controller
{
    public function login(Saml2Auth $saml2Auth)
    {
        $saml2Auth->login(config('saml2_settings.loginRoute'));
    }

    public function logout(Saml2Auth $saml2Auth, Request $request)
    {
        $returnTo = $request->query('returnTo');
        $sessionIndex = $request->query('sessionIndex');
        $nameId = $request->query('nameId');
        $saml2Auth->logout($returnTo, $nameId, $sessionIndex);
    }

    public function acs(Saml2Auth $saml2Auth, $idpName)
    {
        $inputs = [];
        $params = '?invalid=true';
        $url = env('APP_FRONTEND_URL')."/auth/login";

        try {

            $errors = $saml2Auth->acs();

            if (!empty($errors)) {
                logger()->error('Saml2 error_detail', ['error' => $saml2Auth->getLastErrorReason()]);
                session()->flash('saml2_error_detail', [$saml2Auth->getLastErrorReason()]);

                logger()->error('Saml2 error', $errors);
                session()->flash('saml2_error', $errors);

                return redirect("{$url}{$params}");
            }

            $usuarioSaml = $saml2Auth->getSaml2User();

            $inputs = [
                'sso_user_id'  => $usuarioSaml->getUserId(),
                'username'     => $usuarioSaml->getAttribute('http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name'),
                'email'        => $usuarioSaml->getAttribute('http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress'),
                'first_name'   => $usuarioSaml->getAttribute('http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname'),
                'last_name'    => $usuarioSaml->getAttribute('http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname'),
                'password'     => Hash::make('anything'),
             ];

             logger()->error('Saml2 error_detail', ['error' => $saml2Auth->getLastErrorReason()]);

             if(empty($usuarioSaml)){
                return redirect("{$url}{$params}");
             }

            $user = User::firstWhere('email', $inputs['email'][0]);

            if(!empty($user)){

                $user->access_token = sha1(time());
                $user->save();

                $params = "?token={$user->access_token}";
            }

        } catch (\Throwable $th) {
            $params = '?invalid=true';
        }

        return redirect("{$url}{$params}");
    }
}
