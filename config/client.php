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
    'name' => 'VCWeb', /* Nome do site do cliente */
    'subname' => 'Desenvolvedor de sistemas web!', /* Slogan do site do cliente */
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
        'name' => 'VCWeb!',

        /**
         * Google Plus
         */
        'google' => [
            'active' => 0, /* Ativa | Desativa */
            'author' => '', /* ID do Usuário */
            'page' => '', /* ID da Página */
        ],

        /**
         * Facebook
         */
        'facebook' => [
            'active' => 0, /* Ativa | Desativa */
            'app' => 0, /* Opcional APP do facebook */
            'author' => '', /* https://www.facebook.com/????? */
            'page' => '', /* https://www.facebook.com/????? */
            'pageId' => 0, /* ID do Facebook Pages */
        ],

        /**
         * Twitter
         */
        'twitter' => 0, /* https://www.twitter.com/????? */

        /**
         * Youtube
         */
        'youtube' => 0, /* https://www.youtube.com/user/????? */

        /**
         * Instagram
         */
        'instagram' => 0, /* https://www.instagram.com/????? */
    ],

];
