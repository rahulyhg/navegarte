<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2018 Vagner Cardoso
 */

namespace App\Controllers\Api {
    
    use Core\Contracts\Controller;
    use Core\Helpers\Curl;
    use Core\Helpers\Str;
    
    /**
     * Class UtilController
     *
     * @package App\Controllers\Api
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class UtilController extends Controller
    {
        /**
         * @var \Core\Helpers\Curl
         */
        protected $curl;
        
        /**
         * Inicia no __construct
         */
        protected function boot()
        {
            $this->curl = new Curl();
        }
        
        /**
         * [GET] /api/util/{method}[/{data:.*}]
         *
         * @param string $method
         * @param string $params
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        public function get($method, $params = null)
        {
            // Verifica METHOD
            if (!$this->request->isGet()) {
                throw new \Exception("Verbo HTTP aceito é apenas GET.", E_USER_ERROR);
            }
            
            return $this->createRequest($method, $params);
        }
        
        /**
         * [POST] /api/util/{method}[/{data:.*}]
         *
         * @param string $method
         * @param string $params
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        public function post($method, $params = null)
        {
            // Verifica METHOD
            if (!$this->request->isPost()) {
                throw new \Exception("Verbo HTTP aceito é apenas POST.", E_USER_ERROR);
            }
            
            return $this->createRequest($method, $params);
        }
        
        /**
         * [PUT] /api/util/{method}[/{data:.*}]
         *
         * @param string $method
         * @param string $params
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        public function put($method, $params = null)
        {
            // Verifica METHOD
            if (!$this->request->isPut()) {
                throw new \Exception("Verbo HTTP aceito é apenas PUT.", E_USER_ERROR);
            }
            
            return $this->createRequest($method, $params);
        }
        
        /**
         * [DELETE] /api/util/{method}[/{data:.*}]
         *
         * @param string $method
         * @param string $params
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        public function delete($method, $params = null)
        {
            // Verifica METHOD
            if (!$this->request->isDelete()) {
                throw new \Exception("Verbo HTTP aceito é apenas DELETE.", E_USER_ERROR);
            }
            
            return $this->createRequest($method, $params);
        }
        
        /**
         * [GET, POST] /api/util/zipcode/{zipcode}
         *
         * Realiza a pesquisa do CEP e pega a LONGITUDE e LATITUDE
         *
         * @param array $params
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        protected function zipcode($params)
        {
            try {
                // Verifica se o CEP foi passado
                if (empty($params[0])) {
                    throw new \InvalidArgumentException("Você deve passar o CEP para buscar.", E_USER_ERROR);
                }
                
                // Verifica se é válido
                if (strlen(onlyNumber($params[0])) < 8) {
                    throw new \InvalidArgumentException("O CEP {$params[0]} informado deve conter, no mínimo 8 números.", E_USER_ERROR);
                }
                
                // Pega o resultado do CEP
                $result = $this->curl->get("https://viacep.com.br/ws/{$params[0]}/json");
                
                if (!empty($result['erro'])) {
                    throw new \Exception("O CEP {$params[0]} informado não foi encontrado.", E_USER_ERROR);
                }
                
                // Google Maps
                $mapsParams = [
                    'key' => 'AIzaSyCUiWvcqkPMCH_CgTwbkOp74-9oEHlhMOA',
                    'sensor' => true,
                    'address' => urlencode("{$result['logradouro']} - {$result['bairro']}, {$result['localidade']} - {$result['uf']}, {$result['cep']}, Brasil"),
                ];
                
                $maps = $this->curl->get("https://maps.google.com/maps/api/geocode/json", $mapsParams);
                
                if ($maps['status'] === 'OK' && !empty($maps['results'][0])) {
                    $location = $maps['results'][0]['geometry']['location'];
                    
                    $result['latitude'] = (string) $location['lat'];
                    $result['longitude'] = (string) $location['lng'];
                }
                
                return $this->json($result);
            } catch (\Exception $e) {
                throw new \Exception("[ZIP CODE] :: {$e->getMessage()}", $e->getCode());
            }
        }
        
        /**
         * [POST] /api/util/modal-detail
         *
         * Realiza a pesquisa e insere o resultado na modal
         *
         * @param array $params
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        protected function modelDetail($params)
        {
            try {
                // Verifica a view
                if (empty($params['view'])) {
                    throw new \Exception("Você deve passar a view para inserir na modal.", E_USER_ERROR);
                }
                
                // Verifica se foi passado o model
                if (!empty($params['model']) && (!empty($params['id']) && $params['id'] > 0)) {
                    $namespace = "\\App\\Models\\".Str::studly($params['model']);
                    
                    if (!$params['row'] = (new $namespace())->id($params['id'])->limit(1)->get()) {
                        throw new \Exception("Registro não encontrado.", E_USER_ERROR);
                    }
                }
                
                // Retorna resultado
                return $this->json([
                    'object' => [
                        'modalContent' => $this->view->fetch($params['view'], $params),
                    ],
                ]);
            } catch (\Exception $e) {
                throw new \Exception("[MODAL DETAIL] :: {$e->getMessage()}", $e->getCode());
            }
        }
        
        /**
         * Cria a requisição
         *
         * @param string $method
         * @param string $params
         *
         * @return \Slim\Http\Response
         */
        private function createRequest($method, $params = null)
        {
            $params = array_merge(($params ? explode('/', $params) : []), $this->param());
            $method = Str::camel(str_replace('/', '-', $method));
            
            try {
                if (!method_exists($this, $method)) {
                    $method = basename(str_replace('\\', '/', get_class($this)))."::{$method}";
                    
                    throw new \Exception(sprintf("Método (%s) não existe.", $method), E_USER_ERROR);
                }
                
                return $this->{$method}($params);
            } catch (\Exception $e) {
                return $this->json([
                    'error' => [
                        'code' => $e->getCode(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                    ],
                ]);
            }
        }
    }
}
