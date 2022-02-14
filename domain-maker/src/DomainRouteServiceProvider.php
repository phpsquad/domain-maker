<?php

namespace PhpSquad\DomainMaker;

use App\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use PhpSquad\DomainMaker\helpers\DomainPathsProvider;

class DomainRouteServiceProvider extends ServiceProvider
{
  public function boot(): void
  {
    parent::boot();
    $this->registerDomainRoutes();
  }

  public function registerDomainRoutes()
  {
    if (!file_exists(base_path('app/Domains'))) {
      return;
    };

    $domainPaths = DomainPathsProvider::getDomainPaths();

    foreach ($domainPaths as $domainPath) {
      $dirs = array_diff(scandir(base_path($domainPath)), array('.', '..'));
      $routesDirExists = in_array('routes', $dirs);

      if (!$routesDirExists) {
        continue;
      }

      $domainRouteFiles = array_diff(scandir(base_path($domainPath . '/routes')), array('.', '..'));
      foreach ($domainRouteFiles as $file) {
        Route::namespace($this->namespace)
          ->group(base_path($domainPath . '/routes/' . $file));
      }
    }
  }
}
