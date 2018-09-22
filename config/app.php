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
     * App Version
     *
     * Define a versão do framework
     */
    
    'version' => 'v1.2.2',
    
    /**
     * Skeleton Version
     *
     * Define a versão do skeleton
     */
    
    'skeleton' => 'v1.2.1',
    
    /**
     * Session
     *
     * Ativa / Desativa a sessão
     */
    
    'session' => env('APP_SESSION', true),
    
    /**
     * Encryption
     *
     * Define as configurações para a criptografia
     */
    
    'encryption' => [
        'key' => env('APP_KEY'),
        'cipher' => 'AES-256-CBC',
    ],
    
    /**
     * URL
     *
     * Define a URL base
     */
    
    'url' => BASE_URL,
    
    /**
     * Language
     *
     * Define a linguagem padrão
     */
    
    'locale' => env('APP_LOCALE', 'pt_BR'),
    
    /**
     * Maintenance
     *
     * Define se o site vai estar em manutenção
     */
    
    'maintenance' => env('APP_MAINTENANCE', false),
    
    /**
     * Environment
     *
     * Define se o site está em produção ou em desenvolvimento
     */
    
    'environment' => env('APP_ENV', 'production'),
    
    /**
     * Timezone
     *
     * Define o fuso horário do site
     */
    
    'timezone' => env('APP_TIMEZONE', 'America/Sao_Paulo'),

];
