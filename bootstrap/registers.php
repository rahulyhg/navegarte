<?php

return [
    
    /**
     * Register for all containers
     */
    'providers' => [
        \Core\Providers\ErrorServiceProvider::class,
        \Core\Providers\DatabaseServiceProvider::class,
        \Core\Providers\Session\SessionServiceProvider::class,
        \Core\Providers\Hash\BcryptServiceProvider::class,
        \Core\Providers\Encryption\EncryptionServiceProvider::class,
        \Core\Providers\LoggerServiceProvider::class,
        \Core\Providers\View\ViewServiceProvider::class,
        /*Core\Providers\EloquentServiceProvider::class,*/
        \Core\Providers\Mailer\MailerServiceProvider::class,
        /*\Core\Providers\InterventionImageServiceProvider::class,*/
        /*\Core\Providers\WideImageServiceProvider::class,*/
        \Core\Providers\ConfigServiceProvider::class,
    ],
    
    /**
     * Register for all middleware
     */
    'middleware' => [
        \Core\Middleware\ConfigurationMiddleware::class,
        \Core\Middleware\TrailingSlashMiddleware::class,
        \Core\Middleware\OldInputMiddleware::class,
    ],

];
