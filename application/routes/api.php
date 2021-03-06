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

/**
 * API
 *
 * Grupo de rotas para criação das apis
 */

$app->group('/api', function () use ($app) {
    /**
     * Deploy
     *
     * gitlab | bitbucket
     */

    $app->group('/deploy', function () use ($app) {
        $app->route('post', '/gitlab', 'Api/Deploy/GitlabController', 'api.deploy-gitlab', 'cors');
        $app->route('post', '/bitbucket', 'Api/Deploy/BitbucketController', 'api.deploy-bitbucket', 'cors');
    });
    
    /**
     * Criação de api dinâmicas
     */
    
    $app->route('get,post,put,delete,options', '/util/{method:[\w\-]+}[/{params:.*}]', 'Api/UtilController', 'api.util');
});
