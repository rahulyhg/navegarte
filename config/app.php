<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2017 Vagner Cardoso
 */

return [
    
    /**
     * App Version
     *
     * Define a versão do framework
     */
    'version' => 'v1.0.6',
    
    /**
     * Skeleton Version
     *
     * Define a versão do skeleton
     */
    'skeleton' => 'v1.0.2',
    
    /**
     * Configuração para habilitar ou desabilitar as sessões
     */
    'session' => env('APP_SESSION', true),
    
    /**
     * Encryption app
     *
     * Gera a chave do sistema e define se precisa gerar em determinados dias.
     */
    'encryption' => [
        'key' => env('APP_KEY'),
        'cipher' => 'AES-256-CBC',
    ],
    
    /**
     * Application locale configuration
     */
    'locale' => env('APP_LOCALE', 'pt_BR'),
    
    /**
     * App Url
     *
     * Url do sistema
     */
    'url' => BASE_URL,
    
    /**
     * App Maintenance
     *
     * Define se o site vai estar em manutenção
     */
    'maintenance' => env('APP_MAINTENANCE', false),
    
    /**
     * App Environment
     *
     * Define se o site está em produção ou em desenvolvimento
     */
    'environment' => env('APP_ENV', 'production'),
    
    /**
     * App Timezone
     *
     * Define o fuso horário do site
     */
    'timezone' => env('APP_TIMEZONE', 'America/Sao_Paulo'),

];
