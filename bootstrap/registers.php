<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2017 Vagner Cardoso
 */

return [
    /**
     * Register for all containers
     */
    'providers' => [
        'app' => [
            \Core\Providers\View\ViewProvider::class,
            \Core\Providers\Session\SessionProvider::class,
            \Core\Providers\DatabaseProvider::class,
            \Core\Providers\LoggerProvider::class,
            \Core\Providers\Mailer\MailerProvider::class,
            \Core\Providers\Hash\BcryptProvider::class,
            \Core\Providers\Encryption\EncryptionProvider::class,
            \Core\Providers\ErrorProvider::class,
            /*\Core\Providers\EloquentProvider::class,*/
            /*\Core\Providers\InterventionImageProvider::class,*/
            /*\Core\Providers\WideImageProvider::class,*/
        ],
        
        'web' => [],
    ],
    
    /**
     * Register for all functions and helpers
     */
    'functions' => [
        APP_FOLDER.'/app/functions.php',
    ],
    
    /**
     * Register for all middleware
     */
    'middleware' => [
        'app' => [
            \App\Middlewares\MaintenanceMiddleware::class,
            \Core\Middlewares\TrailingSlashMiddleware::class,
            \Core\Middlewares\OldInputMiddleware::class,
        ],
        
        'web' => [],
    ],

];
