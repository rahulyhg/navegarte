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
    $app->group('/deploy', function () use ($app) {
        $app->route('post', '/gitlab', 'Api/Deploy/GitlabController', 'api.deploy-gitlab', 'cors');
        $app->route('post', '/bitbucket', 'Api/Deploy/BitbucketController', 'api.deploy-bitbucket', 'cors');
    });
    
    // Criação de métodos dinamicos
    $app->route('get,post,put,delete,options', '/{method:[\w\-]+}[/{params:.*}]', 'Api/ApiController', 'api.method');
});
