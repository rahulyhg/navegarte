<?php

return [
    
    /**
     * Register for all containers
     */
    'providers' => [
        \Navegarte\Providers\ErrorServiceProvider::class,
        \Navegarte\Providers\DatabaseServiceProvider::class,
        \Navegarte\Providers\Session\SessionServiceProvider::class,
        \Navegarte\Providers\Hash\BcryptServiceProvider::class,
        \Navegarte\Providers\Encryption\EncryptionServiceProvider::class,
        \Navegarte\Providers\LoggerServiceProvider::class,
        \Navegarte\Providers\View\ViewServiceProvider::class,
        /*Navegarte\Providers\EloquentServiceProvider::class,*/
        \Navegarte\Providers\Mailer\MailerServiceProvider::class,
        /*\Navegarte\Providers\InterventionImageServiceProvider::class,*/
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
