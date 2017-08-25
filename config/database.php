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
     * To use Laravel Database you must:
     *
     * composer require illuminate/database
     * And uncomment in bootstrap/registers.php the eloquent provider
     */
    
    /**
     * Conexão padrão
     */
    'default' => env('DB_CONNECTION', 'mysql'),
    
    /**
     * Tipos de conexões
     */
    'connect' => [
        
        /**
         * MySQL
         */
        'mysql' => [
            'driver' => env('DB_DRIVER', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', 'database'),
            'username' => env('DB_USER', 'username'),
            'password' => env('DB_PASS', 'password'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ],
        
        'pgsql' => [
            'driver' => env('DB_DRIVER', 'pgsql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 5432),
            'database' => env('DB_DATABASE', 'database'),
            'username' => env('DB_USERNAME', 'username'),
            'password' => env('DB_PASSWORD', 'password'),
            'charset' => 'utf8',
        ],
        
        'sqlsrv' => [
            'driver' => env('DB_DRIVER', 'sqlsrv'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 1433),
            'database' => env('DB_DATABASE', 'database'),
            'username' => env('DB_USERNAME', 'username'),
            'password' => env('DB_PASSWORD', 'password'),
            'charset' => false,
        ],
    
    ],

];
