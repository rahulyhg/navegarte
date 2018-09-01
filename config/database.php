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
     * Para usar Laravel Database
     *
     * composer require illuminate/database
     * e descomente o EloquentServiceProvider em bootstrap/registers.php
     */
    
    /**
     * Conexão padrão
     */
    'default' => env('DB_DRIVER', 'mysql'),
    
    /**
     * Tipos de conexões
     */
    'connections' => [
        
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
            'collation' => 'utf8mb4_general_ci',
        ],
        
        /**
         * MSSQL Windows
         */
        'sqlsrv' => [
            'driver' => env('DB_DRIVER', 'sqlsrv'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 1433),
            'database' => env('DB_DATABASE', 'database'),
            'username' => env('DB_USER', 'username'),
            'password' => env('DB_PASS', 'password'),
            'charset' => false,
        ],
        
        /**
         * MSSQL Linux
         */
        'dblib' => [
            'driver' => env('DB_DRIVER', 'dblib'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 1433),
            'database' => env('DB_DATABASE', 'database'),
            'username' => env('DB_USER', 'username'),
            'password' => env('DB_PASS', 'password'),
            'charset' => false,
        ],
    
    ],

];
