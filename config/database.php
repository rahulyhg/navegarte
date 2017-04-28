<?php

return [
  
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
      'driver' => 'mysql',
      'host' => env('DB_HOST', 'localhost'),
      'port' => env('DB_PORT', 3306),
      'database' => env('DB_DATABASE', 'framework'),
      'username' => env('DB_USER', 'root'),
      'password' => env('DB_PASS', 'root'),
      'charset' => 'utf8',
      'collation' => 'utf8_general_ci',
    ],
  
  ],

];
