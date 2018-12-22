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

namespace App\Controllers\Api {
    
    use Core\Contracts\Controller;
    
    /**
     * Class GitlabController
     *
     * @package App\Controllers\Api
     * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class GitlabController extends Controller
    {
        /**
         * [POST] /api/deploy-gitlab
         *
         * @return \Slim\Http\Response
         */
        public function post()
        {
            try {
                // Headers
                $token = $this->request->getHeaderLine('X-Gitlab-Token');
                $event = $this->request->getHeaderLine('X-Gitlab-Event');
                
                if (empty($token) || $token !== env('GITLAB_TOKEN')) {
                    throw new \Exception("Token inválid.", E_USER_ERROR);
                }
                
                // Body
                $body = json_decode(file_get_contents('php://input'), true);
                
                if (empty($body['ref'])) {
                    throw new \InvalidArgumentException("Body ref empty.", E_USER_ERROR);
                }
                
                // Trata branch
                list($ref, $head, $branch) = explode('/', $body['ref']);
                
                // Muda o diretório para a raiz
                chdir(ROOT);
                
                // Verifica pasta .git
                if (!file_exists(ROOT.'/.git')) {
                    throw new \Exception("Git not initialize.", E_USER_ERROR);
                }
                
                switch ($branch) {
                    case 'master':
                        `git fetch origin && git reset --hard origin/master 2>&1`;
                        break;
                    default:
                        throw new \Exception("Branch undefined.", E_USER_ERROR);
                }
                
                return $this->json([
                    'error' => false,
                    'message' => 'Deploy gitlab successfully.',
                ], 200);
            } catch (\Exception $e) {
                return json_error($e);
            }
        }
    }
}
