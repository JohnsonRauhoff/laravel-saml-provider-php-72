<?php namespace CharlesRumley\Saml\Http\Controllers;

use CharlesRumley\Saml\Events\SamlLoginEvent;
use CharlesRumley\Saml\Events\SamlLogoutEvent;
use CharlesRumley\Saml\Saml;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OneLogin_Saml2_Auth;

class SamlController extends Controller
{

    public function metadata(Saml $saml)
    {
        $metadata = $saml->metadata();

        return response($metadata, 200, ['Content-Type' => 'text/xml']);
    }

    public function acs(Saml $saml, OneLogin_Saml2_Auth $samlProvider)
    {
        $samlProvider->processResponse();
        $errors = $samlProvider->getErrors();

        if (!empty($errors)) {
            return $errors;
        }
        if (!$samlProvider->isAuthenticated()) {
            return array('error' => 'Could not authenticate');
        }

        // todo handle errors

        event(new SamlLoginEvent($saml->user()));

        $redirectUrl = $saml->user()->intendedUrl();
        if ($redirectUrl) {
            return redirect($redirectUrl);
        }

        return redirect(route(config('saml.loginRoute')));
    }

    public function sls(Saml $saml, OneLogin_Saml2_Auth $samlProvider)
    {
        $samlProvider->processSLO(
            false,
            null,
            true,
            function () {
                // todo clear session
            }
        );

        $errors = $samlProvider->getErrors();

        // todo handle errors

        event(new SamlLogoutEvent($saml->user()));

        return redirect('saml.logout');
    }

    public function logout(Request $request, OneLogin_Saml2_Auth $samlProvider)
    {
        // todo document these parameters
        // todo allow returnTo to be overriden in config
        $returnTo = $request->query('returnTo', route('/'));
        $sessionIndex = $request->query('sessionIndex', $samlProvider->getSessionIndex());
        $nameId = $request->query('nameId', $samlProvider->getNameId());

        $samlProvider->logout($returnTo, [], $nameId, $sessionIndex);

        // OneLogin handles the response
        return null;
    }

    public function login(OneLogin_Saml2_Auth $samlProvider)
    {
        $samlProvider->login(route(config('saml.loginRoute')));
    }

}