<?php

return [
  
  /**
   * Register for all containers
   */
  'providers' => [
    \Navegarte\Providers\ErrorServiceProvider::class,
    \Navegarte\Providers\Session\SessionServiceProvider::class,
    \Navegarte\Providers\Hash\BcryptServiceProvider::class,
    \Navegarte\Providers\LoggerServiceProvider::class,
    \Navegarte\Providers\View\ViewServiceProvider::class,
    /*Navegarte\Providers\EloquentServiceProvider::class,*/
    \Navegarte\Providers\Mailer\MailerServiceProvider::class,
  ],
  
  /**
   * Register for all middleware
   */
  'middleware' => [
    \Navegarte\Middleware\ConfigurationMiddleware::class,
    \Navegarte\Middleware\TrailingSlashMiddleware::class,
    \Navegarte\Middleware\OldInputMiddleware::class,
  ],

];
