<?php

return [
  
  /**
   * Configuração do site
   */
  'name' => 'Navegarte', /* Nome do site do cliente */
  'subname' => 'Desenvolvimento de websites, softwares para seu negócio!', /* Slogan do site do cliente */
  'description' => 'Descrição com máximo de 160 caracteres', /* Descrição do site do cliente */
  
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
    'name' => 'Navegarte networks!',
    
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
      'app' => 0, /* Opcional APP do facebook */
      'author' => 'vagnercardosoweb', /* https://www.facebook.com/????? */
      'page' => 'facebook', /* https://www.facebook.com/????? */
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
