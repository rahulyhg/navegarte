<?php

return [
    
    /**
     * App Version
     *
     * Define a versão do framework
     */
    'version' => 'v0.0.0',
    
    /**
     * App Version
     *
     * Define a versão do skeleton
     */
    'skeleton' => 'v0.0.0',
    
    /**
     * Encryption app
     *
     * Gera a chave do sistema e define se precisa gerar em determinados dias.
     */
    'encryption' => [
        'key' => env('APP_KEY'),
        'cipher' => 'AES-256-CBC',
        'regenerate.days' => false
    ],
    
    /**
     * App Url
     *
     * Url do sistema
     */
    'url' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']),
    
    /**
     * App Maintenance
     *
     * Define se o site vai estar em manutenção
     */
    'maintenance' => false,
    
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
