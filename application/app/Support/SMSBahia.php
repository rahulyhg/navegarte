<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 12/01/2019 Vagner Cardoso
 */

namespace App\Support {
    
    use Core\Helpers\Curl;
    use Core\Helpers\Helper;
    
    /**
     * Class SMSBahia
     *
     * @package App\Support
     * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class SMSBahia
    {
        /**
         * @var string
         */
        protected $endPoint = 'http://maxx.mobi/api/sms/EnviosRetornos';
        
        /**
         * @var string|null
         */
        protected $username;
        
        /**
         * @var string|null
         */
        protected $token;
        
        /**
         * @var array
         */
        protected $data = [];
        
        /**
         * Sms constructor.
         *
         * @param string|null $username
         * @param string|null $token
         *
         * @throws \Exception
         */
        public function __construct($username = null, $token = null)
        {
            // Verifica se existe a função env
            if (function_exists('env')) {
                $username = env('SMS_USERNAME', null);
                $token = env('SMS_TOKEN', null);
            }
            
            // Atributos
            $this->username = $username;
            $this->token = $token;
        }
        
        /**
         * @return array
         * @throws \Exception
         */
        public function send()
        {
            try {
                $response = (new Curl())->post($this->endPoint, $this->data);
                
                // Verifica json
                if (!is_array($response) && !$response = Helper::checkJson((string) $response)) {
                    throw new \Exception("Retorno inválido.", E_USER_ERROR);
                }
                
                // Verifica se ocorreu erro
                if (!empty($response['Descricao']) && $response['Descricao'] !== 'OK') {
                    throw new \Exception($response['Descricao'], E_USER_ERROR);
                }
                
                return $response;
            } catch (\Exception $e) {
                throw $e;
            }
        }
        
        /**
         * @param string|null $username
         *
         * @return SMSBahia
         */
        public function username($username)
        {
            $this->username = $username;
            
            return $this;
        }
        
        /**
         * @param string|null $token
         *
         * @return SMSBahia
         */
        public function token($token)
        {
            $this->token = $token;
            
            return $this;
        }
        
        /**
         * @param array $data
         *
         * @return SMSBahia
         */
        public function data(array $data)
        {
            // Parametros obrigatórios
            $data['Serial'] = (!empty($data['Serial']) ? $data['Serial'] : '5M53A1A');
            $data['Ws'] = (!empty($data['Ws']) ? $data['Ws'] : '01');
            $data['User'] = (!empty($data['User']) ? $data['User'] : $this->username);
            $data['Token'] = (!empty($data['Token']) ? $data['Token'] : $this->token);
            $data['Numero'] = (!empty($data['Numero']) ? $data['Numero'] : null);
            $data['Mensagem'] = (!empty($data['Mensagem']) ? $data['Mensagem'] : null);
            
            // Validações
            if (empty($data['Numero']) || empty($data['Mensagem'])) {
                throw new \InvalidArgumentException("Você deve passar o número e a mensagem para envio do sms.", E_USER_ERROR);
            }
            
            // Valida número
            if (strlen($data['Numero']) < 13 || strlen($data['Numero']) > 13) {
                throw new \InvalidArgumentException("Número de telefone deve ser apenas DDI+DDD+NUMERO, exemplo: (5571900000000).", E_USER_ERROR);
            }
            
            // Validaçõe de autenticação
            if (empty($data['User']) || empty($data['Token'])) {
                throw new \InvalidArgumentException("Dados de autentição inválidos.", E_USER_ERROR);
            }
            
            $this->data = $data;
            
            return $this;
        }
    }
}
