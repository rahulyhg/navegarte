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
     * Eventos
     *
     * tbName:creating | tbName:created
     * tbName:updating | tbName:updated
     * tbName:deleting | tbName:deleted
     */
    
    /**
     * Default
     *
     * Driver de conexão padrão
     */
    
    'default' => env('DB_DRIVER', 'mysql'),
    
    /**
     * Options
     *
     * Configura as opções para conexão
     */
    
    'options' => [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
        \PDO::ATTR_PERSISTENT => false,
    ],
    
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
            'dsn' => 'mysql:host=%s;dbname=%s',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', ''),
            'username' => env('DB_USER', ''),
            'password' => env('DB_PASS', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATE', 'utf8mb4_general_ci'),
        ],
        
        /**
         * MSSQL
         */
        
        'dblib' => [
            //'dsn' => 'sqlsrv:Server=%s;Connect=%s;ConnectionPooling=0',
            'dsn' => 'dblib:version=7.0;charset=UTF-8;host=%s;dbname=%s',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 1433),
            'database' => env('DB_DATABASE', ''),
            'username' => env('DB_USER', ''),
            'password' => env('DB_PASS', ''),
            'charset' => false,
            'collation' => false,
        ],
    
    ],

];
