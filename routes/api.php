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

/**
 * API
 *
 * Grupo de rotas para criação das apis
 */

$app->group('/api', function () use ($app) {
    // Deploy gitlab
    $app->route('post', '/deploy-gitlab', 'Api/GitlabController', 'api.deploy-gitlab', 'cors');
    
    // Criação de métodos dinamicos
    $app->route('get,post,put,delete', '/{method:[\w\-]+}[/{params:.*}]', 'Api/UtilController', 'api.util');
});
