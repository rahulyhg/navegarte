<?php

return [
  
  /**
   * Register for all containers
   */
  'providers' => [
    /*\App\Core\Providers\ErrorServiceProvider::class,*/
    Navegarte\Providers\LoggerServiceProvider::class,
    Navegarte\Providers\View\ViewServiceProvider::class,
    /*Navegarte\Providers\EloquentServiceProvider::class,*/
    Navegarte\Providers\Mailer\MailerServiceProvider::class,
  ],
  
  /**
   * Register for all middleware
   */
  'middleware' => [
    Navegarte\Middleware\ConfigurationMiddleware::class,
    Navegarte\Middleware\TrailingSlashMiddleware::class,
  ],

];
