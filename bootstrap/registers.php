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
     * Providers
     *
     * Registra todos os serviços disponíveis
     */
    
    'providers' => [
        
        /**
         * Providers da aplicação
         */
        
        'app' => [
            \Core\Providers\View\ViewProvider::class,
            \Core\Providers\Session\SessionProvider::class,
            \Core\Providers\DatabaseProvider::class,
            \Core\Providers\LoggerProvider::class,
            \Core\Providers\Mailer\MailerProvider::class,
            \Core\Providers\Hash\HashProvider::class,
            \Core\Providers\Encryption\EncryptionProvider::class,
            \Core\Providers\ErrorProvider::class,
        ],
        
        /**
         * Providers custom
         */
        
        'web' => [],
    ],
    
    /**
     * Functions
     *
     * Registra as funções customizadas
     */
    
    'functions' => [
        APP_FOLDER.'/app/functions.php',
    ],
    
    /**
     * Middlewares
     *
     * Registra todas middleware disponíveis
     */
    
    'middleware' => [
        
        /**
         * Middlewares da aplicação
         */
        
        'app' => [
            \App\Middlewares\MaintenanceMiddleware::class,
            \Core\Middlewares\TrailingSlashMiddleware::class,
            \Core\Middlewares\OldInputMiddleware::class,
        ],
        
        /**
         * Middlewares custom
         */
        
        'web' => [],
    ],

];
