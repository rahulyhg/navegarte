<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 26/02/2019 Vagner Cardoso
 */

use Core\App;

/**
 * Constantes
 */

define('ROOT', __DIR__);
define('PUBLIC_FOLDER', __DIR__.'/public');
define('APP_FOLDER', __DIR__.'');
define('RESOURCE_FOLDER', __DIR__.'/resources');
define('BASE_URL', "");

/**
 * Composer
 *
 * Carrega o autoload
 */

$pathAutoload = APP_FOLDER.'/vendor/autoload.php';

if (!file_exists($pathAutoload)) {
    die(
        'Run command in terminal: <br>'.
        '<code style="background: #000; color: #fff;">composer install</code>'
    );
}

require_once "{$pathAutoload}";

/**
 * App
 *
 * Inicia as configurações da aplicação
 */

try {
    /**
     * Instancia da classe
     */
    
    $app = App::getInstance();
    
    /**
     * Inicia os serviços
     */
    
    $app->registerProviders([
        \Core\Providers\Database\DatabaseProvider::class,
        \Core\Providers\Encryption\EncryptionProvider::class,
        \Core\Providers\Hash\HashProvider::class,
        \Core\Providers\Jwt\JwtProvider::class,
    ]);
    
    /**
     * Configurações do phinx
     */
    
    return [
        /**
         * Class padrão para a migração
         */
        
        'migration_base_class' => \Core\Contracts\Migration::class,
        
        /**
         * Template para criação da migration
         */
        
        'templates' => [
            'file' => __DIR__.'/storage/database/templates/Migration.php.dist',
        ],
        
        /**
         * Caminhos relativos até a pasta que será salvo os arquivos
         * de migrations, seeds e bootstrapper
         */
        
        'paths' => [
            'migrations' => __DIR__.'/storage/database/migrations',
            'seeds' => __DIR__.'/storage/database/seeds',
        ],
        
        /**
         * Configurações que é usada no escopo do phinx
         */
        
        'environments' => [
            'default_migration_table' => 'migrations',
            'default_database' => env('DB_DRIVER', 'mysql'),
            
            /**
             * MySQL
             */
            
            'mysql' => [
                'adapter' => 'mysql',
                'host' => env('DB_HOST', 'localhost'),
                'port' => env('DB_PORT', 3306),
                'name' => env('DB_DATABASE', ''),
                'user' => env('DB_USER', ''),
                'pass' => env('DB_PASS', ''),
                'charset' => env('DB_CHARSET', 'utf8mb4'),
                'collation' => env('DB_COLLATE', 'utf8mb4_general_ci'),
                'table_prefix' => false,
                'table_suffix' => false,
            ],
            
            /**
             * MSSQL
             */
            
            'dblib' => [
                'adapter' => 'sqlsrv',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', 1433),
                'name' => env('DB_DATABASE', ''),
                'user' => env('DB_USER', ''),
                'pass' => env('DB_PASS', ''),
                'charset' => false,
                'collation' => false,
                'table_prefix' => false,
                'table_suffix' => false,
            ],
        ],
        
        /**
         * Ordem da versionamento
         *
         * creation:
         * - As migrações são ordenadas pelo tempo de criação, que também faz parte do nome do arquivo.
         *
         * execution:
         * - As migrações são ordenadas pelo tempo de execução, também conhecido como horário de início.
         */
        
        'version_order' => 'creation',
    ];
} catch (\Exception $e) {
    dd($e);
}
