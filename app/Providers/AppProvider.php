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

namespace App\Providers {
    
    use Core\Contracts\Provider;
    
    /**
     * Class AppProvider
     *
     * @package App\Providers
     * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class AppProvider extends Provider
    {
        /**
         * Registra os serviços padrões da aplicação
         *
         * @return void
         */
        public function register()
        {
            $this->container['name'] = function () {
                return 'name';
            };
        }
        
        /**
         * Registra outros serviços no escopo do provider
         *
         * @return void
         */
        public function boot()
        {
            $this->view->addFunction('csrf_token', function ($input = true) {
                $token = \Core\App::getInstance()->resolve('encryption')->encrypt([
                    'token' => uniqid(rand(), true),
                    'expired' => time() + (60 * 60 * 24),
                ]);
                
                return $input
                    ? "<input type='hidden' name='_csrfToken' id='_csrfToken' value='{$token}'/>"
                    : $token;
            });
        }
    }
}
