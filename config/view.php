<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2018 Vagner Cardoso
 */

return [
    
    /**
     * Options
     *
     * Configura as opções padões do twig
     */
    
    'options' => [
        'debug' => true,
        'charset' => 'UTF-8',
        'strict_variables' => false,
        'autoescape' => 'html',
        'cache' => (env('APP_ENV') === 'production' ? APP_FOLDER.'/storage/cache' : false),
        'auto_reload' => true,
        'optimizations' => -1,
    ],
    
    /**
     * Templates
     *
     * Define os templates no carregamento das views
     *
     * OBS: Não altere os nomes dos indice e caso altere o caminho do template
     *      deverá verificar e renomear o nome da pasta.
     */
    
    'templates' => [
        'web' => RESOURCE_FOLDER.'/views/web',
        'error' => RESOURCE_FOLDER.'/views/error',
        'mail' => RESOURCE_FOLDER.'/views/mail',
    ],
    
    /**
     * Helpers & Functions
     *
     * Registra funções e filtros para usar na view
     */
    
    'registers' => [
        
        /**
         * Funções
         */
        
        'functions' => [
            'path_for' => 'path_for',
            'config' => 'config',
            'asset' => 'asset',
            'asset_source' => 'asset_source',
            'has_route' => 'has_route',
            'is_route' => 'is_route',
            'dd' => 'dd',
        ],
        
        /**
         * Filtros
         */
        
        'filters' => [
            'is_string' => 'is_string',
            'is_array' => 'is_array',
            'get_day' => 'get_day',
            'get_month' => 'get_month',
        ],
    
    ],

];
