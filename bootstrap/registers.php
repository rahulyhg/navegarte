<?php

return [
  
  /**
   * Register for all containers
   */
  'providers' => [
    /*\App\Core\Providers\ErrorServiceProvider::class,*/
    \App\Core\Providers\LoggerServiceProvider::class,
    \App\Core\Providers\View\ViewServiceProvider::class,
    /*\App\Core\Providers\EloquentServiceProvider::class,*/
    \App\Core\Providers\Mailer\MailerServiceProvider::class,
  ],
  
  /**
   * Register for all middleware
   */
  'middleware' => [
    \App\Core\Middleware\ConfigurationMiddleware::class,
    \App\Core\Middleware\TrailingSlashMiddleware::class,
  ],

];
