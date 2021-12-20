<?php

namespace PhpSquad\DomainMaker;

use App\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class DomainRouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();
        $this->registerDomainRoutes();
    }

    public function registerDomainRoutes()
    {
        $domains = array_diff(scandir(base_path('app/Domains')), array('.', '..'));

        foreach($domains as $domain) {
            $dirs = array_diff(scandir(base_path('app/Domains/' . $domain)), array('.', '..'));
            $routesDirExists = in_array('routes', $dirs);
            if (!$routesDirExists){
                Log::info("$domain Domain missing routes directory. Can't register routes");
                continue;
            }

            $domainRouteFiles  = array_diff(scandir(base_path('app/Domains/' . $domain. '/routes')), array('.', '..'));
            foreach($domainRouteFiles as $file){
                Route::prefix('api')
                    ->middleware('api')
                    ->namespace($this->namespace)
                    ->group(base_path('app/Domains/' . $domain. '/routes/'.$file));
            }
        }
    }
}
