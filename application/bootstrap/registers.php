<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>.
 *
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 * @copyright 2017-2018 Vagner Cardoso
 */

return [
    /*
     * Providers
     *
     * Registra todos os serviços disponíveis
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
        \Core\Providers\Logger\LoggerProvider::class,
        \Core\Providers\Event\EventProvider::class,
        
        /*
         * Registra serviço da aplicação
         */
        
        \App\Providers\ErrorSlackProvider::class,
    ],
    
    /*
     * Functions
     *
     * Registra os arquivos de funções
     */
    
    'functions' => [
        APP_FOLDER.'/app/functions.php',
    ],
    
    /*
     * Middlewares
     *
     * Registra todas middleware disponíveis
     */
    
    'middleware' => [
        /*
         * Middlewares automáticas
         */
        
        'app' => [
            \Core\Middlewares\GenerateAppKeyMiddleware::class,
            \Core\Middlewares\TrailingSlashMiddleware::class,
            \Core\Middlewares\MaintenanceMiddleware::class,
            \Core\Middlewares\OldInputMiddleware::class,
        ],
        
        /*
         * Middlewares manuais
         */
        
        'web' => [
            'cors' => \App\Middlewares\CorsMiddleware::class,
        ],
    ],
];
