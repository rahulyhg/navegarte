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
     * Configuração do site
     */
    'name' => 'VCWeb Networks', /* Nome do site do cliente */
    'subname' => 'Desenvolvendo soluções digitais!', /* Slogan do site do cliente */
    'description' => '', /* Descrição do site do cliente */
    'abstract' => '',
    'keywords' => '',
    
    /**
     * Dados da empresa do cliente
     */
    'company' => [
        'name' => '', /* Nome de remetente */
        'rs' => '', /* Razão Social */
        'email' => '', /* E-mail de contato */
        'site' => '', /* URL descrita */
        'cnpj' => '', /* CNPJ da empresa */
        'ie' => '', /* Inscrição estadual da empresa */
        'phoneA' => '', /* Telefone 1 */
        'phoneB' => '', /* Telefone 2 */
        
        /**
         * Endereço
         */
        'address' => [
            'street' => '', /* Nome da rua */
            'number' => '', /* Número da casa */
            'complement' => '', /* Complemento (casa,ap,etc...) */
            'city' => '', /* Nome Cidade */
            'district' => '', /* Nome do bairro */
            'state' => '', /* UF do estado */
            'zipCode' => '', /* CEP da sua rua */
            'country' => '', /* País */
        ],
    ],
    
    /**
     * Redes sociais
     */
    'social' => [
        'name' => 'VCWeb Networks!',
        
        /**
         * Google Plus
         */
        'google' => [
            'active' => 1, /* Ativa | Desativa */
            'author' => '114194145304748321596', /* ID do Usuário */
            'page' => '114194145304748321596', /* ID da Página */
        ],
        
        /**
         * Facebook
         */
        'facebook' => [
            'active' => 1, /* Ativa | Desativa */
            'app' => '342941756145265', /* Opcional APP do facebook */
            'author' => 'vagnercardosoweb', /* https://www.facebook.com/????? */
            'page' => 'vcwebnetworks', /* https://www.facebook.com/????? */
            'pageId' => '555309298001978', /* ID do Facebook Pages */
        ],
        
        /**
         * Twitter
         */
        'twitter' => 'vcwebnetworks', /* https://www.twitter.com/????? */
        
        /**
         * Youtube
         */
        'youtube' => 0, /* https://www.youtube.com/user/????? */
        
        /**
         * Instagram
         */
        'instagram' => 'vcwebnetworks', /* https://www.instagram.com/????? */
    ],

];
