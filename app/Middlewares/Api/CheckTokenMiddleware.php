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

namespace App\Middlewares\Api {
    
    use Core\Contracts\Middleware;
    use Slim\Http\Request;
    use Slim\Http\Response;
    use Slim\Http\StatusCode;
    
    /**
     * Class CheckTokenMiddleware
     *
     * @package App\Middlewares\Api
     * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class CheckTokenMiddleware extends Middleware
    {
        /**
         * Registra middleware para verificação do token e csrf
         *
         * @param \Slim\Http\Request $request PSR7 request
         * @param \Slim\Http\Response $response PSR7 response
         * @param callable $next Next middleware
         *
         * @return \Slim\Http\Response
         */
        public function __invoke(Request $request, Response $response, callable $next)
        {
            try {
                // Variáveis
                $payload = [];
                $type = '';
                $token = '';
                $authorization = $request->getHeaderLine('Authorization');
                
                if (empty($authorization)) {
                    $token = $request->getHeaderLine('X-Csrf-Token');
                    
                    if (!empty($token)) {
                        $type = 'Basic';
                    } else {
                        throw new \Exception("Acesso não autorizado.", E_USER_ERROR);
                    }
                }
                
                if (preg_match('/^(Basic|Bearer)\s+(.*)/i', $authorization, $matches)) {
                    array_shift($matches);
                    
                    if (count($matches) !== 2) {
                        throw new \Exception("Tipo e token inválido.", E_USER_ERROR);
                    }
                    
                    $type = trim($matches[0]);
                    $token = trim($matches[1]);
                }
                
                if (!in_array($type, ['Basic', 'Bearer'])) {
                    throw new \Exception("Tipo de autorização não aceito.", E_USER_ERROR);
                }
                
                if (empty($token)) {
                    throw new \Exception("Token inválido.", E_USER_ERROR);
                }
                
                if ($type === 'Basic' && ($token !== env('API_TOKEN_BASIC') && !$payload = $this->jwt->decode($token))) {
                    throw new \Exception("Token inválido para o tipo de autorização.", E_USER_ERROR);
                }
                
                if ($type === 'Bearer' && !$payload = $this->jwt->decode($token)) {
                    throw new \Exception("Token inválido.", E_USER_ERROR);
                }
                
                if (!empty($payload['expired']) && $payload['expired'] < time()) {
                    throw new \Exception("Token expirado.", E_USER_ERROR);
                }
                
                if ($type === 'Bearer' && !empty($payload['id'])) {
                    // Verifica usuário e cria o container `auth`
                }
            } catch (\Exception $e) {
                return json_error($e, [], StatusCode::HTTP_UNAUTHORIZED);
            }
            
            $response = $next($request, $response);
            
            return $response;
        }
    }
}
