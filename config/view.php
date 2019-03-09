<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 09/03/2019 Vagner Cardoso
 */

use Core\App;

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
     */
    
    'template' => [
        'view' => RESOURCE_FOLDER.'/view',
        'mail' => RESOURCE_FOLDER.'/mail',
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
            'placeholder' => 'placeholder',
            'has_container' => [App::getInstance(), 'resolve'],
            'csrf_token' => function ($input = true) {
                $token = App::getInstance()->resolve('encryption')->encrypt([
                    'token' => uniqid(rand(), true),
                    'expired' => time() + (60 * 60 * 24),
                ]);
                
                return $input
                    ? "<input type='hidden' name='_csrfToken' id='_csrfToken' value='{$token}'/>"
                    : $token;
            },
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
