<?php

/**
 * VCWeb <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2018 Vagner Cardoso
 */

namespace App\Providers {
    
    use Core\Contracts\Provider;
    
    /**
     * Class SystemProvider
     *
     * @package App\Providers
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class SystemProvider extends Provider
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
        }
    }
}
