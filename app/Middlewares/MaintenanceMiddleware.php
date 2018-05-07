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

namespace App\Middlewares {
    
    use Core\Contracts\Middleware;
    use Slim\Http\Request;
    use Slim\Http\Response;
    
    /**
     * Class MaintenanceMiddleware
     *
     * @package Core\Middlewares
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    final class MaintenanceMiddleware extends Middleware
    {
        /**
         * Register middleware
         *
         * @param \Slim\Http\Request  $request  PSR7 request
         * @param \Slim\Http\Response $response PSR7 response
         * @param callable            $next     Next middleware
         *
         * @return \Slim\Http\Response
         * @throws \Exception
         */
        public function __invoke(Request $request, Response $response, callable $next)
        {
            if (!empty(config('app.maintenance')) && config('app.maintenance') === true) {
                // Verifica se tem o template criado
                if (!file_exists(config('view.path.folder').'/maintenance')) {
                    try {
                        // Cria a pasta de manutenção
                        mkdir(config('view.path.folder').'/maintenance');
                        
                        // Cria o template conforme a engine configurada
                        file_put_contents(
                            config('view.path.folder').'/maintenance/home.'.config('view.engine'),
                            $this->{"getTemplate".ucfirst(config('view.engine'))}()
                        );
                    } catch (\Exception $e) {
                        throw new \Exception($e->getMessage(), $e->getCode());
                    }
                }
                
                // Cria a resposta
                $response->getBody()
                    ->write($this->view->fetch('maintenance.home'));
                
                // Retorna a resposta
                return $response;
            }
            
            $response = $next($request, $response);
            
            return $response;
        }
        
        /**
         * Template
         *
         * @return string
         */
        protected function getTemplateTwig()
        {
            return sprintf("<p style='text-align:center;font-weight:bold;margin-top:50px;font-size:30px;'>%s</p>", "Estamos em manutenção, voltaremos logo.");
        }
    }
}
