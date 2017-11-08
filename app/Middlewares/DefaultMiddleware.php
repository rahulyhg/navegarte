<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2017 Vagner Cardoso
 */

namespace App\Middlewares {

    use Core\Contracts\Middleware;
    use Slim\Http\Request;
    use Slim\Http\Response;

    /**
     * Class DefaultMiddleware
     *
     * @package App\Middlewares
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    final class DefaultMiddleware extends Middleware
    {
        /**
         * Register middleware
         *
         * @param \Slim\Http\Request  $request  PSR7 request
         * @param \Slim\Http\Response $response PSR7 response
         * @param callable            $next     Next middleware
         *
         * @return \Slim\Http\Response
         */
        public function __invoke(Request $request, Response $response, callable $next)
        {
            $response = $next($request, $response);

            return $response;
        }
    }
}
