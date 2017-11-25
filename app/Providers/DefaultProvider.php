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

namespace App\Providers {

    use Core\Contracts\Provider;

    /**
     * Class DefaultProvider
     *
     * @package App\Providers
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    final class DefaultProvider extends Provider
    {
        /**
         * Registers services on the given container.
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
         * Inicializa junto com o service
         */
        public function boot()
        {
        }
    }
}
