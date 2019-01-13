<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 12/01/2018 Vagner Cardoso
 */

namespace App\Controllers\Api {
    
    use Core\App;
    use Core\Contracts\Controller;
    use Core\Helpers\Curl;
    use Core\Helpers\Str;
    
    /**
     * Class ApiController
     *
     * @package App\Controllers\Api
     * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class ApiController extends Controller
    {
        /**
         * Emula os métodos (get, post, put, delete, options, etc...)
         *
         * @param string $name
         * @param mixed $arguments
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        public function __call($name, $arguments)
        {
            if (!empty($name) && !empty($arguments)) {
                // Se for o método options retorna
                if ($name === 'options') {
                    return $this->response;
                }
                
                // Verifica se o méthod existe
                if (!method_exists(App::class, $name)) {
                    throw new \Exception("Invalid requisition method.", E_USER_ERROR);
                }
                
                // Variáveis
                $method = $arguments[0];
                $params = (!empty($arguments[1]) ? $arguments[1] : '');
                
                // Realiza requisição
                return $this->request($method, $params);
            }
        }
        
        /**
         * @param string $method
         * @param string $params
         *
         * @return \Slim\Http\Response
         * @throws \Slim\Exception\NotFoundException
         */
        protected function request($method, $params = null)
        {
            // Variáveis
            $method = Str::camel(str_replace('/', '-', $method));
            $params = array_merge(($params ? explode('/', $params) : []), $this->param());
            
            // Veririca se o método existe
            if (!method_exists($this, $method)) {
                $this->notFound();
            }
            
            try {
                return $this->{$method}($params);
            } catch (\Exception $e) {
                return json_error($e);
            }
        }
        
        /**
         * [GET, POST] /api/zipcode/{zipcode}
         *
         * @param array $params
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        protected function zipcode(array $params)
        {
            // Models
            $curl = new Curl();
            
            // Verifica parametro
            if (!empty($params[0])) {
                $params['cep'] = $params[0];
            }
            
            try {
                // Verifica se o CEP foi passado
                if (empty($params['cep'])) {
                    throw new \InvalidArgumentException("Você deve passar o CEP para buscar.", E_USER_ERROR);
                }
                
                // Verifica se é válido
                if (strlen(onlyNumber($params['cep'])) < 8) {
                    throw new \InvalidArgumentException("O CEP {$params['cep']} informado deve conter, no mínimo 8 números.", E_USER_ERROR);
                }
                
                // Pega o resultado do CEP
                $result = $curl->get("https://viacep.com.br/ws/{$params['cep']}/json");
                
                if (!empty($result['erro'])) {
                    throw new \Exception("O CEP {$params['cep']} informado não foi encontrado.", E_USER_ERROR);
                }
                
                // Formata endereço
                $result['endereco'] = "{$result['logradouro']} - {$result['bairro']}, {$result['localidade']} - {$result['uf']}, {$result['cep']}, Brasil";
                
                // Google Maps
                $mapsParams = [
                    'key' => 'AIzaSyCUiWvcqkPMCH_CgTwbkOp74-9oEHlhMOA',
                    'sensor' => true,
                    'address' => urlencode($result['endereco']),
                ];
                
                $maps = $curl->get("https://maps.google.com/maps/api/geocode/json", $mapsParams);
                
                if ($maps['status'] === 'OK' && !empty($maps['results'][0])) {
                    $location = $maps['results'][0]['geometry']['location'];
                    
                    $result['latitude'] = (string) $location['lat'];
                    $result['longitude'] = (string) $location['lng'];
                }
                
                return $this->json($result);
            } catch (\Exception $e) {
                throw new \Exception("[ZIP CODE] {$e->getMessage()}", $e->getCode());
            }
        }
        
        /**
         * [POST] /api/modal-detail
         *
         * @param array $params
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        protected function modelDetail(array $params)
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
                throw new \Exception("[MODAL DETAIL] {$e->getMessage()}", $e->getCode());
            }
        }
    }
}
