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
     * Configuração de e-mail
     *
     * Verifique os dados com seu provedor de e-mail
     */
    
    /**
     * 0 = Off ( Em produção manter em off )
     * 1 = Mensagem cliente
     * 2 = Mensagem do servidor e cliente
     */
    'debug' => 0,
    
    'charset' => 'utf-8',
    
    'host' => env('MAIL_HOST', 'mail.domain.com'),
    
    'port' => env('MAIL_PORT', 587),
    
    'username' => env('MAIL_USER', 'support@domain.com'),
    
    'password' => env('MAIL_PASS', 'password'),
    
    'auth' => env('MAIL_AUTH', true),
    
    'secure' => env('MAIL_SECURE', 'tls'),
    
    'from' => [
        'mail' => '',
        'name' => '',
    ],
    
    'reply' => [
        'mail' => '',
        'name' => '',
    ],
    
    'language' => [
        'path' => null,
        'name' => 'pt_br',
    ],

];
