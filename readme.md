# SAML Provider for Laravel

Laravel 5

SAML 2.0

## Single Login Process
Begin SAML login flow by redirect to the `saml.login` route. User will be returned to `defaultIntendedRoute` as specified in `saml.php` after user has successfully completed SAML login:

    return redirect(route('saml.login'));
    
Optionally include an intended URL parameter where the user should be redirected after completing a successful login:
    
    return redirect(route('saml.login', ['intended-url' => $intendedUrl']));

## Service Providers
Register the following service providers in `app.php`

    'providers' => [
        // ...
        
        CharlesRumley\Saml\Providers\SamlServiceProvider::class,
        CharlesRumley\Saml\Providers\SamlRouteServiceProvider::class,        
    ],