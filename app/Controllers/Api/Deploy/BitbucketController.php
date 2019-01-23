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

namespace App\Controllers\Api\Deploy {
    
    use Core\Contracts\Controller;
    use Slim\Http\StatusCode;
    
    /**
     * Class BitbucketController
     *
     * @package App\Controllers\Api\Deploy
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class BitbucketController extends Controller
    {
        /**
         * [POST] /api/deploy-bitbucket
         *
         * @return \Slim\Http\Response
         */
        public function post()
        {
            try {
                // Token
                $token = $this->param('token');
                
                if ($token !== env('DEPLOY_TOKEN')) {
                    throw new \Exception("Token inválid.", E_USER_ERROR);
                }
                
                // Body
                $body = json_decode(file_get_contents('php://input'), true);
                
                if (empty($body['push']['changes'])) {
                    throw new \InvalidArgumentException("Body push changes empty.", E_USER_ERROR);
                }
                
                if (($countChange = count($body['push']['changes'])) > 0) {
                    $lastChange = $body['push']['changes'][$countChange - 1]['new'];
                    
                    // Verify type
                    if ($lastChange['type'] !== 'branch') {
                        throw new \Exception("Type change new diff branch.", E_USER_ERROR);
                    }
                    
                    // Muda o diretório para a raiz
                    chdir(ROOT);
                    
                    // Verifica pasta .git
                    if (!file_exists(ROOT.'/.git')) {
                        throw new \Exception("Git not initialize.", E_USER_ERROR);
                    }
                    
                    switch ($lastChange['name']) {
                        case 'master':
                            `git fetch origin && git reset --hard origin/master 2>&1`;
                            break;
                        default:
                            throw new \Exception("Branch undefined.", E_USER_ERROR);
                    }
                } else {
                    throw new \Exception("Count change less than 0.", E_USER_ERROR);
                }
                
                return $this->json([
                    'error' => false,
                    'message' => 'Deploy bitbucket successfully.',
                ], 200);
            } catch (\Exception $e) {
                return json_error($e, [], StatusCode::HTTP_BAD_REQUEST);
            }
        }
    }
}
