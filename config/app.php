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
     * Define a versão da aplicação
     */
    
    'version' => [
        
        /**
         * Versão do framework
         */
        
        'framework' => 'v1.2.14',
        
        /**
         * Versão do skeleton
         */
        
        'skeleton' => 'v1.2.8',
    
    ],
    
    /**
     * Session
     *
     * Ativa / Desativa a sessão
     */
    
    'session' => env('APP_SESSION', true),
    
    /**
     * Language
     *
     * Define a linguagem padrão
     */
    
    'locale' => env('APP_LOCALE', 'pt_BR'),
    
    /**
     * Maintenance
     *
     * Define se a aplicação vai estar em manutenção
     */
    
    'maintenance' => env('APP_MAINTENANCE', false),
    
    /**
     * Environment
     *
     * Define se a aplicaão está em produção ou em desenvolvimento
     */
    
    'environment' => env('APP_ENV', 'production'),
    
    /**
     * Timezone
     *
     * Define o fuso horário da aplicaão
     */
    
    'timezone' => env('APP_TIMEZONE', 'America/Sao_Paulo'),

];
