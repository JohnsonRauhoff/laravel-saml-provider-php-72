<?php namespace CharlesRumley\Saml\Providers;

use CharlesRumley\Saml\Console\Commands\GenerateIdentityProviderSettingsFromMetadata;
use CharlesRumley\Saml\Console\Commands\GenerateServiceProviderMetadata;
use CharlesRumley\Saml\Saml;
use Illuminate\Support\ServiceProvider;
use OneLogin\Saml2\Auth;
use OneLogin\Saml2\Utils;

class SamlServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    protected $commands = [
        GenerateIdentityProviderSettingsFromMetadata::class,
        GenerateServiceProviderMetadata::class
    ];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/saml.php' => config_path('saml.php'),
            ]
        );

        if (config('saml.proxyVars', false)) {
            Utils::setProxyVars(true);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);

        $this->app->singleton(
            Auth::class,
            function ($app) {
                $config = config('saml.onelogin_configuration');
                $config['sp']['entityId'] = $config['sp']['entityId'] ?: url('/', [], true);
                $config['sp']['assertionConsumerService']['url'] = url(route('saml.acs', [], false), [], true);
                $config['sp']['singleLogoutService']['url'] = url(route('saml.sls', [], false), [], true);

                return new Auth($config);
            }
        );

        $this->app->singleton(
            Saml::class,
            function ($app) {
                return new Saml($app->make(Auth::class));
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}