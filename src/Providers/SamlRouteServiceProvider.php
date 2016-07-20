<?php namespace CharlesRumley\Saml\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class SamlRouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'CharlesRumley\Saml\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define additional routes for the application
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(
            [
                'namespace' => $this->namespace,
                'as' => 'saml.',
                'prefix' => config('saml.endpointUrlPrefix', 'saml')
            ],
            function ($router) {
                require __DIR__ . '/../Http/routes.php';
            }
        );
    }
}
