<?php

return [
  
  /**
   * Configuração de e-Mail
   *
   * Consulte esses dados com o serviço de hospedagem
   *
   * Obs: Em Implementação!!!
   */

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

];
