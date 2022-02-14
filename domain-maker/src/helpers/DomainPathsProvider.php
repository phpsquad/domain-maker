<?php

namespace PhpSquad\DomainMaker\helpers;

class DomainPathsProvider
{
  public static function getDomainPaths(): array
  {
    if (!file_exists(base_path('app/Domains'))) {
      return [];
    };

    $domains = array_diff(scandir(base_path('app/Domains')), array('.', '..'));

    $domainPaths = [];
    foreach($domains as $domain) {
      $domainPaths[] = 'app/Domains/' . $domain;
    }

    return $domainPaths;
  }
}
