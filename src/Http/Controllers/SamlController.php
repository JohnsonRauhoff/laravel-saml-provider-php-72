<?php namespace CharlesRumley\Saml\Http\Controllers;

use CharlesRumley\Saml\Events\SamlLoginEvent;
use CharlesRumley\Saml\Events\SamlLogoutEvent;
use CharlesRumley\Saml\Saml;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OneLogin\Saml2\Auth;

class SamlController extends Controller
{

    public function metadata(Saml $saml)
    {
        $metadata = $saml->metadata();

        return response($metadata, 200, ['Content-Type' => 'text/xml']);
    }

    public function acs(Saml $saml, Auth $samlProvider)
    {
        $samlProvider->processResponse();

        $this->assertSuccessState($samlProvider);

        if (!$samlProvider->isAuthenticated()) {
            return array('error' => 'Could not authenticate');
        }

        event(new SamlLoginEvent($saml->user()));

        $redirectUrl = $saml->user()->intendedUrl();
        if ($redirectUrl) {
            return redirect($redirectUrl);
        }

        return redirect(route(config('saml.defaultIntendedRoute')));
    }

    /**
     * Ensure the current state of the given OneLogin SAML provider is successful, throw
     * an exception if the provider is in a failure state.
     */
    private function assertSuccessState(Auth $samlProvider)
    {
        $firstError = @$samlProvider->getErrors()[0];
        $lastErrorReason = $samlProvider->getLastErrorReason();

        if (!empty($firstError)) {
            // TODO: This exception doesn't read well
            throw new Exception(
                "Asserting SAML provider in successful state failed. An '${firstError}' error with reason '$lastErrorReason' was found"
            );
        }
    }

    public function sls(Saml $saml, Auth $samlProvider)
    {
        $samlProvider->processSLO(
            false,
            null,
            true,
            function () use ($saml) {
                event(new SamlLogoutEvent($saml->user()));
            }
        );

        $this->assertSuccessState($samlProvider);

        return redirect(route(config('saml.logoutRoute')));
    }

    public function logout(Request $request, Auth $samlProvider)
    {
        // todo document these parameters
        $returnTo = $request->query('returnTo', route(config('saml.logoutRoute')));
        $sessionIndex = $request->query('sessionIndex', $samlProvider->getSessionIndex());
        $nameId = $request->query('nameId', $samlProvider->getNameId());

        $samlProvider->logout($returnTo, [], $nameId, $sessionIndex);

        // OneLogin handles the response
        return null;
    }

    public function login(Request $request, Auth $samlProvider)
    {
        $intendedUrl = $request->get('intended-url', url(route(config('saml.defaultIntendedRoute'))));

        $samlProvider->login($intendedUrl);
    }

}