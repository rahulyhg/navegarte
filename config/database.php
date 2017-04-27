<?php

return [
  
  /**
   * Conexão padrão
   */
  'default' => 'mysql',
  
  /**
   * Tipos de conexões
   */
  'connect' => [
    
    /**
     * MySQL
     */
    'mysql' => [
      'driver' => 'mysql',
      'host' => 'db',
      'port' => '3306',
      'database' => 'framework',
      'username' => 'root',
      'password' => 'root',
      'charset' => 'utf8',
      'collation' => 'utf8_general_ci',
    ],
  
  ],

];
