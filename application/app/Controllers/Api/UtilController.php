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
     * Class UtilController
     *
     * @package App\Controllers\Api
     * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class UtilController extends Controller
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
                if (in_array($name, ['options', 'patch'])) {
                    return $this->response;
                }
                
                // Verifica se o méthod existe
                if (!method_exists(App::class, $name)) {
                    throw new \Exception("Invalid requisition method.", E_USER_ERROR);
                }
                
                // Variáveis
                $method = Str::camel(str_replace('/', '-', $arguments[0]));
                $path = (!empty($arguments[1]) ? $arguments[1] : '');
                $data = array_merge(($path ? explode('/', $path) : []), $this->param());
                
                // Veririca se o método existe
                if (!method_exists($this, $method)) {
                    throw new \BadMethodCallException(
                        sprintf("Call to undefined method %s::%s()", get_class($this), $method), E_ERROR
                    );
                }
                
                try {
                    return $this->{$method}($data);
                } catch (\Exception $e) {
                    throw $e;
                }
            }
        }
        
        /**
         * [GET, POST] /api/zipcode/{zipcode}
         *
         * @param array $data
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        protected function zipcode(array $data)
        {
            // Models
            $curl = new Curl();
            
            // Verifica parametro
            if (!empty($data[0])) {
                $data['cep'] = $data[0];
            }
            
            try {
                // Verifica se o CEP foi passado
                if (empty($data['cep'])) {
                    throw new \InvalidArgumentException("Você deve passar o CEP para buscar.", E_USER_ERROR);
                }
                
                // Verifica se é válido
                if (strlen(onlyNumber($data['cep'])) < 8) {
                    throw new \InvalidArgumentException("O CEP {$data['cep']} informado deve conter, no mínimo 8 números.", E_USER_ERROR);
                }
                
                // Pega o resultado do CEP
                $result = $curl->get("https://viacep.com.br/ws/{$data['cep']}/json");
                
                if (!empty($result['erro'])) {
                    throw new \Exception("O CEP {$data['cep']} informado não foi encontrado.", E_USER_ERROR);
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
                throw $e;
            }
        }
        
        /**
         * [POST] /api/modal-detail
         *
         * @param array $data
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        protected function modalDetail(array $data)
        {
            try {
                // Verifica a view
                if (empty($data['view'])) {
                    throw new \Exception(
                        "Você deve passar a view para inserir na modal.", E_USER_ERROR
                    );
                }
                
                // Verifica se foi passado o model
                if (!empty($data['model']) && (!empty($data['id']) && $data['id'] > 0)) {
                    $model = "\\App\\Models\\".Str::studly($data['model']);
                    
                    if (!$data['row'] = (new $model())->reset()->fetchById($data['id'])) {
                        throw new \Exception("Registro não encontrado.", E_USER_ERROR);
                    }
                }
                
                // Retorna resultado
                return $this->json([
                    'object' => [
                        'modalContent' => $this->view->fetch($data['view'], $data),
                    ],
                ]);
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }
}
