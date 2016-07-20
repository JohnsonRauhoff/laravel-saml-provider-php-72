<?php namespace CharlesRumley\Saml;

use OneLogin_Saml2_Auth;

class User
{
    protected $samlProvider;

    function __construct(OneLogin_Saml2_Auth $samlProvider)
    {
        $this->samlProvider = $samlProvider;
    }

    public function id()
    {
        return $this->nameId();
    }

    public function nameId()
    {
        return $this->samlProvider->getNameId();
    }

    public function attributes()
    {
        return $this->samlProvider->getAttributes();
    }

    public function intendedUrl()
    {
        // todo don't like getting instance of request from app here
        $relayState = app('request')->input('RelayState');

        $url = app('Illuminate\Contracts\Routing\UrlGenerator');

        if ($relayState && $url->full() != $relayState) {
            return $relayState;
        }
    }

    public function sessionIndex()
    {
        return $this->samlProvider->getSessionIndex();
    }

}