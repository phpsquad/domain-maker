<?php

namespace PhpSquad\DomainMaker;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\View\FileViewFinder;
use Illuminate\View\ViewServiceProvider as ServiceProvider;

class ViewProvider extends ServiceProvider
{
  public function register()
  {
    $this->registerFactory();
    $this->registerViewFinder();
    $this->registerBladeCompiler();
    $this->registerEngineResolver();
  }

  public function registerViewFinder()
  {
    $domainMakerViews =  $this->getDomainViewPaths();



    $this->app->bind('view.finder', function ($app) use ($domainMakerViews) {

      $paths = $app['config']['view.paths'];

      if ($domainMakerViews){
          foreach ($domainMakerViews as $path) {
              $paths[] = $path;
          }
      }


      return new FileViewFinder($app['files'], $paths);
    });
  }

  public function getDomainViewPaths()
  {
    $viewPaths = [];
    if (!file_exists(base_path('app/Domains'))) {
      return;
    };

    $domains = array_diff(scandir(base_path('app/Domains')), array('.', '..'));

    foreach ($domains as $domain) {
      $dirs = array_diff(scandir(base_path('app/Domains/' . $domain)), array('.', '..'));
      $resourcesDirExists = in_array('resources', $dirs);
      if (!$resourcesDirExists) {
        continue;
      }

      $dirs = array_diff(scandir(base_path('app/Domains/' . $domain . '/resources')), array('.', '..'));
      $routesDirExists = in_array('views', $dirs);

      if (!$routesDirExists) {
        continue;
      }

      $viewPaths[] = base_path('app/Domains/' . $domain . '/resources/views');
    }

    return $viewPaths;
  }
}
