<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2018 Vagner Cardoso
 */

return [
    
    /**
     * Define a versão da aplicação
     */
    
    'version' => [
        
        /**
         * Versão do framework
         */
        
        'framework' => 'v2.0.1',
        
        /**
         * Versão do skeleton
         */
        
        'skeleton' => 'v2.0.1',
    
    ],
    
    /**
     * Registra os serviços
     */
    
    'providers' => [
        \Core\Providers\View\ViewProvider::class,
        \Core\Providers\ErrorProvider::class,
        \Core\Providers\Session\SessionProvider::class,
        \Core\Providers\Database\DatabaseProvider::class,
        \Core\Providers\Mailer\MailerProvider::class,
        \Core\Providers\Encryption\EncryptionProvider::class,
        \Core\Providers\Hash\HashProvider::class,
        \Core\Providers\Jwt\JwtProvider::class,
        \Core\Providers\LoggerProvider::class,
    ],
    
    /**
     * Registra as middlewares
     */
    
    'middlewares' => [
        
        /**
         * Middlewares iniciada automática
         */
        
        'automatic' => [
            \Core\Middlewares\GenerateAppKeyMiddleware::class,
            \Core\Middlewares\TrailingSlashMiddleware::class,
            \Core\Middlewares\MaintenanceMiddleware::class,
            \Core\Middlewares\OldInputMiddleware::class,
        ],
        
        /**
         * Middlewares iniciada manual
         */
        
        'manual' => [],
    
    ],

];
