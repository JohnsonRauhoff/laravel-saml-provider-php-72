<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Logout Route
    |--------------------------------------------------------------------------
    |
    | Specify the route name where user will be redirected after logout
    |
    */

    'logoutRoute' => '/',

    /*
    |--------------------------------------------------------------------------
    | Default Intended Route
    |--------------------------------------------------------------------------
    |
    | Specify the route name where user will be redirected after successful authentication
    |
    | You may also specify an optional 'intended-url' parameter when redirecting to SAML. If 'intended-url'
    | is specified, this configuration option will not be used.
    |
    | Example:
    |
    |    redirect(route('saml.login', ['intended-url', $intendedUrl]));
    |
    */

    'defaultIntendedRoute' => '/',

    /*
    |--------------------------------------------------------------------------
    | Default URL Prefix
    |--------------------------------------------------------------------------
    |
    | Specify the URL prefix used by all endpoints
    |
    | Default: saml
    |
    | Example:
    | saml/acs
    | saml/sls
    |
    */

    'endpointUrlPrefix' => 'saml',

    /*
    |--------------------------------------------------------------------------
    | Assertion Customer Service Endpoint URL
    |--------------------------------------------------------------------------
    |
    | Specify the URL endpoint for ACS. Default URL prefix will be prepended to
    | this value
    |
    | Default: acs
    |
    */

    'acsUrl' => 'acs',

    /*
    |--------------------------------------------------------------------------
    | Single Logout Service Endpoint URL
    |--------------------------------------------------------------------------
    |
    | Specify the URL endpoint for SLS. Default URL prefix will be prepended to
    | this value
    |
    | Default: sls
    |
    */

    'slsUrl' => 'sls',

    /*
    |--------------------------------------------------------------------------
    | Metadata Endpoint URL
    |--------------------------------------------------------------------------
    |
    | Specify the URL endpoint for serving metadata. Default URL prefix will be
    | prepended to this value
    |
    | Default: metadata
    |
    */

    'metadataUrl' => 'metadata',

    /*
    |--------------------------------------------------------------------------
    | OneLogin Configuration
    |--------------------------------------------------------------------------
    |
    | These settings allow OneLogin configuration to be overridden or modified.
    | They're copied from OneLogin's configuration example
    |
    | TODO: Removed included comments due to poor English, rewrite based on source
    | TODO: Move strict and debug to package-level configuration
    */

    'onelogin_configuration' => [

        'strict' => true,

        'debug' => false,

        'sp' => [
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
            'x509cert' => '',
            'privateKey' => '',
            'entityId' => '', // blank results in automatic generation
            'assertionConsumerService' => [
                'url' => '',
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            ],
            'singleLogoutService' => [
                'url' => '',
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ],
        ],

        'idp' => [
            'entityId' => '',
            'singleSignOnService' => [
                'url' => '',
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ],
            'singleLogoutService' => [
                'url' => '',
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ],
            'x509cert' => '',
        ],

        /*
        |--------------------------------------------------------------------------
        | OneLogin Advanced Configuration
        |--------------------------------------------------------------------------
        |
        | TODO: Removed included comments due to poor English, rewrite based on source
        |
        */

        'security' => [
            'nameIdEncrypted' => false,
            'authnRequestsSigned' => false,
            'logoutRequestSigned' => false,
            'logoutResponseSigned' => false,
            'signMetadata' => false,
            'wantMessagesSigned' => false,
            'wantAssertionsSigned' => false,
            'wantNameIdEncrypted' => false,
            'requestedAuthnContext' => true,
        ],

        'contactPerson' => array(
            'technical' => array(
                'givenName' => 'name',
                'emailAddress' => 'no@reply.com'
            ),
            'support' => array(
                'givenName' => 'Support',
                'emailAddress' => 'no@reply.com'
            ),
        ),

        'organization' => array(
            'en-US' => array(
                'name' => 'Name',
                'displayname' => 'Display Name',
                'url' => 'http://url'
            ),
        ),
    ]
];