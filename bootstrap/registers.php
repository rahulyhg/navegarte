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
        ],
    
        'web' => [
    
        ]
    ],
    
    /**
     * Register for all middleware
     */
    'middleware' => [
        'app' => [
            'config' => \Core\Middleware\ConfigurationMiddleware::class,
            'slash' => \Core\Middleware\TrailingSlashMiddleware::class,
            'old' => \Core\Middleware\OldInputMiddleware::class,
        ],
    
        'web' => [
    
        ],
    ],

];
