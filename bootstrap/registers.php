<?php

return [
  
  /**
   * Register for all containers
   */
  'providers' => [
    \App\Core\Providers\ErrorServiceProvider::class,
    \App\Core\Providers\TwigServiceProvider::class,
    \App\Core\Providers\LoggerServiceProvider::class,
    /*\App\Core\Providers\EloquentServiceProvider::class,*/
  ],
  
  /**
   * Register for all middleware
   */
  'middleware' => [
    \App\Core\Middleware\ConfigurationMiddleware::class,
    \App\Core\Middleware\TrailingSlashMiddleware::class,
  ]

];
