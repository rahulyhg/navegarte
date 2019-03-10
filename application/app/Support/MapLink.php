<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 07/02/2019 Vagner Cardoso
 */

namespace App\Support {
    
    use App\Models\FreteWay\Frete\Frete;
    use Core\Helpers\Curl;
    
    /**
     * Class MapLink
     *
     * @package App\Support
     * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class MapLink
    {
        /**
         * @var string
         */
        protected $baseUrl = 'https://lbslocal-prod.apigee.net';
        
        /**
         * @var string
         */
        protected $clientId;
        
        /**
         * @var string
         */
        protected $clientSecret;
        
        /**
         * MapLink constructor.
         *
         * @param string|null $clientId
         * @param string|null $clientSecret
         *
         * @throws \Exception
         */
        public function __construct($clientId = null, $clientSecret = null)
        {
            // Verifica se existe a configuração no env
            if (function_exists('env') && (empty($clientId) && empty($clientSecret))) {
                $clientId = env('MAPLINK_CLIENT_ID', null);
                $clientSecret = env('MAPLINK_CLIENT_SECRET', null);
            }
            
            // Propriedades
            $this->clientId = (string) $clientId;
            $this->clientSecret = (string) $clientSecret;
            
            // Valida os dados
            if (empty($this->clientId) || empty($this->clientSecret)) {
                throw new \Exception(
                    sprintf("[%s()] Dados inválidos, não foi possível identificar o client_id e client_secret.", __METHOD__), E_USER_ERROR
                );
            }
        }
        
        /**
         * @throws \Exception
         */
        public function oauth()
        {
            try {
                // Monta parâmetros
                $params = [
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ];
                
                // Envia a requisição
                $response = (new Curl())->post("{$this->baseUrl}/oauth/client_credential/accesstoken?grant_type=client_credentials", $params);
                
                if (empty($response['access_token'])) {
                    $response = json_encode($response);
                    
                    throw new \Exception(
                        sprintf("<p><b>[%s()] Não foi possível autenticar.</b></p><code>%s</code>", __METHOD__, $response), E_USER_ERROR
                    );
                }
                
                return $response;
            } catch (\Exception $e) {
                throw $e;
            }
        }
        
        /**
         * @param int $codFrete
         *
         * @return array|false|string
         * @throws \Exception
         */
        public function problems($codFrete)
        {
            try {
                // Verifica o frete
                $frete = (new Frete())->reset()
                    ->fetchById($codFrete);
                
                if (empty($frete)) {
                    throw new \Exception(
                        sprintf("[%s()] Frete passado é inválido.", __METHOD__), E_USER_ERROR
                    );
                }
                
                // Verifica a latitude e longitude
                foreach (['ORIGEM', 'DESTINO'] as $item) {
                    if (empty($frete["{$item}_LAT_FRETE"])) {
                        throw new \Exception(
                            sprintf("[%s()] Latitude de {$item} não pode ser vázio.", __METHOD__), E_USER_ERROR
                        );
                    }
                    
                    if (empty($frete["{$item}_LON_FRETE"])) {
                        throw new \Exception(
                            sprintf("[%s()] Longitude de {$item} não pode ser vázio.", __METHOD__), E_USER_ERROR
                        );
                    }
                }
                
                // Monta parâmetros
                $params = [
                    "callback" => [
                        "password" => "fw_frete_{$codFrete}",
                        "url" => BASE_URL.path_for('api.v1.frete.mapa-maplink', ['id' => $codFrete]),
                        "user" => md5($codFrete),
                    ],
                    "profileName" => "BRAZIL",
                    "calculationMode" => "THE_FASTEST",
                    "startDate" => 0,
                    "points" => [
                        [
                            "latitude" => $frete['ORIGEM_LAT_FRETE'],
                            "longitude" => $frete['ORIGEM_LON_FRETE'],
                            "siteId" => "Origem Frete {$codFrete}",
                        ],
                        [
                            "latitude" => $frete['DESTINO_LAT_FRETE'],
                            "longitude" => $frete['DESTINO_LON_FRETE'],
                            "siteId" => "Destino Frete {$codFrete}",
                        ],
                    ],
                ];
                
                // Faz autenticação
                $oauth = $this->oauth();
                
                // Envia requisição
                $response = (new Curl())->setHeaders("Authorization: Bearer {$oauth['access_token']}")
                    ->post("{$this->baseUrl}/trip/v1/problems", json_encode($params));
                
                if (empty($response['id'])) {
                    $response = json_encode($response);
                    
                    throw new \Exception(
                        sprintf("<p><b>[%s()] Não foi possível enviar seu problema.</b></p><code>%s</code>", __METHOD__, $response), E_USER_ERROR
                    );
                }
                
                // Salva os dados na tabela
                $maplink = (new \App\Models\FreteWay\Frete\MapLink())->data([
                    'COD_FRETE' => $codFrete,
                    'ID_SOLUCAO' => $response['id'],
                ])->save();
                
                return array_merge($frete, $maplink);
            } catch (\Exception $e) {
                throw $e;
            }
        }
        
        /**
         * @param int $jobId
         *
         * @return array|false|string
         * @throws \Exception
         */
        public function solution($jobId)
        {
            try {
                // Faz autenticação
                $oauth = $this->oauth();
                
                // Envia requisição
                $response = (new Curl())->setHeaders("Authorization: Bearer {$oauth['access_token']}")
                    ->get("{$this->baseUrl}/trip/v1/solutions/{$jobId}");
                
                if (empty($response['id'])) {
                    $response = json_encode($response);
                    
                    throw new \Exception(
                        sprintf("<p><b>[%s()] Não foi possível recuperar a solução.</b></p><code>%s</code>", __METHOD__, $response), E_USER_ERROR
                    );
                }
                
                return $response;
            } catch (\Exception $e) {
                throw $e;
            }
        }
        
        /**
         * @param int $points
         *
         * @return array|false|string
         * @throws \Exception
         */
        public function calculations($points)
        {
            try {
                // Parâmetros
                $params = [
                    "legs" => [
                        [
                            "vehicleType" => "TRUCK_WITH_TWO_SINGLE_AXIS",
                            "points" => $points,
                        ],
                    ],
                ];
                
                // Faz autenticação
                $oauth = $this->oauth();
                
                // Envia requisição
                $response = (new Curl())->setHeaders("Authorization: Bearer {$oauth['access_token']}")
                    ->post("{$this->baseUrl}/toll/v1/calculations", json_encode($params));
                
                if (empty($response['legs'])) {
                    $response = json_encode($response);
                    
                    throw new \Exception(
                        sprintf("<p><b>[%s()] Não foi possível recuperar os pedagios.</b></p><code>%s</code>", __METHOD__, $response), E_USER_ERROR
                    );
                }
                
                return $response;
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }
}
