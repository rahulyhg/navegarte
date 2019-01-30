<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 12/01/2018 Vagner Cardoso
 */

return [
    
    /**
     * Default
     *
     * Driver de conexão padrão
     */
    
    'default' => env('DB_DRIVER', 'mysql'),
    
    /**
     * Drivers
     *
     * Define os tipo de conexões que serão aceitos
     */
    
    'connections' => [
        
        /**
         * MySQL
         */
        
        'mysql' => [
            'driver' => env('DB_DRIVER', 'mysql'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', ''),
            'username' => env('DB_USER', ''),
            'password' => env('DB_PASS', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATE', 'utf8mb4_general_ci'),
        ],
        
        /**
         * MSSQL Windows
         */
        
        'sqlsrv' => [
            'driver' => env('DB_DRIVER', 'sqlsrv'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 1433),
            'database' => env('DB_DATABASE', ''),
            'username' => env('DB_USER', ''),
            'password' => env('DB_PASS', ''),
            'charset' => env('DB_CHARSET', ''),
            'collation' => env('DB_COLLATE', ''),
        ],
        
        /**
         * MSSQL Linux
         */
        
        'dblib' => [
            'driver' => env('DB_DRIVER', 'dblib'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 1433),
            'database' => env('DB_DATABASE', ''),
            'username' => env('DB_USER', ''),
            'password' => env('DB_PASS', ''),
            'charset' => env('DB_CHARSET', ''),
            'collation' => env('DB_COLLATE', ''),
        ],
    
    ],

];
