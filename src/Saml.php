<?php namespace CharlesRumley\Saml;

use OneLogin\Saml2\Auth;

class Saml
{
    /**
     * @var Auth
     */
    protected $samlProvider;
    protected $samlAssertion;

    function __construct(Auth $auth)
    {
        $this->samlProvider = $auth;
    }

    public function isAuthenticated()
    {
        return $this->samlProvider->isAuthenticated();
    }

    public function user()
    {
        return new User($this->samlProvider);
    }

    public function initiateLogin($redirectTo = null)
    {
        $this->samlProvider->login($redirectTo);
    }

    public function initiateLogout($redirectTo = null, $nameId = null, $sessionIndex = null)
    {
        $this->samlProvider->logout($redirectTo, [], $nameId, $sessionIndex);
    }

    public function metadata()
    {
        $settings = $this->samlProvider->getSettings();
        $metadata = $settings->getSPMetadata();
        $errors = $settings->validateMetadata($metadata);

        if ($errors) {
            // todo handle errors
        }

        return $metadata;
    }
}