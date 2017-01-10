# SAML Provider for Laravel

Laravel 5

SAML 2.0

## Service Providers
Register the following service providers in `app.php`

    'providers' => [
        // ...
        
        CharlesRumley\Saml\Providers\SamlServiceProvider::class,
        CharlesRumley\Saml\Providers\SamlRouteServiceProvider::class,        
    ],