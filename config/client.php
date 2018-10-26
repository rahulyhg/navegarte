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
     * Configuração do site
     */
    
    'name' => 'VCWeb Networks', /* Nome do site do cliente */
    'subname' => '', /* Slogan do site do cliente */
    'description' => '', /* Descrição do site do cliente */
    'abstract' => '',
    'keywords' => '',
    
    /**
     * Redes sociais
     */
    
    'social' => [
        'name' => 'VCWeb Networks',
        
        /**
         * Google Plus
         */
        
        'google' => [
            'active' => false, /* Ativa | Desativa */
            'author' => '', /* ID do Usuário */
            'page' => '', /* ID da Página */
        ],
        
        /**
         * Facebook
         */
        
        'facebook' => [
            'active' => false, /* Ativa | Desativa */
            'app' => '', /* Opcional APP do facebook */
            'author' => '', /* https://www.facebook.com/????? */
            'page' => '', /* https://www.facebook.com/????? */
            'pageId' => '', /* ID do Facebook Pages */
        ],
        
        /**
         * Twitter
         */
        
        'twitter' => false, /* https://www.twitter.com/????? */
        
        /**
         * Youtube
         */
        
        'youtube' => false, /* https://www.youtube.com/user/????? */
        
        /**
         * Instagram
         */
        
        'instagram' => false, /* https://www.instagram.com/????? */
    ],

];
