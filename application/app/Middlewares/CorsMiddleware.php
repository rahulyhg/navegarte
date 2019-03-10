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

namespace App\Middlewares {
    
    use Core\Contracts\Middleware;
    use Slim\Http\Request;
    use Slim\Http\Response;
    
    /**
     * Class CorsMiddleware
     *
     * @package App\Middlewares
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class CorsMiddleware extends Middleware
    {
        /**
         * Registra middleware para verificar o CORS
         *
         * @param \Slim\Http\Request $request PSR7 request
         * @param \Slim\Http\Response $response PSR7 response
         * @param callable $next Next middleware
         *
         * @return \Slim\Http\Response
         */
        public function __invoke(Request $request, Response $response, callable $next)
        {
            /** @var Response $response */
            $response = $next($request, $response);
            
            /*header_remove("Cache-Control");
            header_remove("Expires");
            header_remove("Pragma");*/
            
            $response = $response->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', implode(', ', [
                    'X-Requested-With',
                    'X-Http-Method-Override',
                    'Content-Type',
                    'Accept',
                    'Origin',
                    'Authorization',
                    'X-Csrf-Token',
                ]))
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
            
            return $response;
        }
    }
}
