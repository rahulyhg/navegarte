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
    use Core\Helpers\Curl;
    use Core\Helpers\Helper;
    
    /**
     * Class ErrorSlackProvider
     *
     * @package App\Providers
     * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
     */
    class ErrorSlackProvider extends Provider
    {
        /**
         * Registra serviço para se comunicar com o slack enviando os erros
         * na api de serviço para um maior controle sobre os erros do cliente
         *
         * @return void
         */
        public function register()
        {
            // Apenas é disparado caso não seja em ambiente de desenvolvimento
            if (!preg_match('/localhost|.dev|.local/i', $_SERVER['HTTP_HOST'])) {
                $this->event->on('event.error.handler', function ($errors) {
                    if (!empty($errors['error']) && !empty(env('SLACK_ERROR_URL', ''))) {
                        unset($errors['error']['trace']);
                        
                        // Hash
                        $id = hash_hmac('sha1', json_encode($errors['error']), 'SLACKNOTIFICATION');
                        
                        // Adiciona um novo evento para gerar o id.
                        $this->event->on('event.error.id', function () use ($id) {
                            echo $id;
                        });
                        
                        // Envia a notificação
                        if ($this->checkFilemtime($id)) {
                            $this->sendNotification(array_merge(['id' => $id], $errors['error']));
                        }
                    }
                });
            }
        }
        
        /**
         * @param array $error
         */
        protected function sendNotification($error)
        {
            // Deixa prosseguir a aplicação
            if (!is_php_cli() && env('APP_SESSION', true)) {
                session_write_close();
            }
            
            // Variáveis
            $ip = Helper::getIpAddress();
            $hostname = gethostbyaddr($ip);
            $text = [];
            $text[] = "*ip:* {$ip}";
            $text[] = "*hostname:* {$hostname}";
            $text[] = "*date:* ".date('d/m/Y H:i:s', time());
            
            // Monta payload do erro
            foreach ($error as $key => $value) {
                $text[] = "*error.{$key}:* {$value}";
            }
            
            // Monta payload do browser
            foreach (Helper::getUserAgent() as $key => $value) {
                $text[] = "*browser.{$key}:* {$value}";
            }
            
            // Monta text
            $text = implode(PHP_EOL, $text);
            
            // Envia o payload
            try {
                (new Curl())->post(env('SLACK_ERROR_URL'), json_encode([
                    'text' => $text,
                    "username" => config('client.name'),
                    "mrkdwn" => true,
                ]));
            } catch (\Exception $e) {
                logger('Slack notification', $error, 'error', 'slack');
            }
        }
        
        /**
         * @param string $id
         *
         * @return bool
         */
        protected function checkFilemtime($id)
        {
            // Time do arquivo
            $filename = $this->createFile($id);
            $filetime = filemtime($filename);
            $time = time();
            
            // Verifica se pode enviar a notificação
            if ($filetime <= $time) {
                touch($filename, ($time + env('SLACK_ERROR_INTERVAL', 60)));
                
                return true;
            }
            
            return false;
        }
        
        /**
         * @param string $id
         *
         * @return string
         */
        protected function createFile($id)
        {
            // Variáveis
            $path = APP_FOLDER.'/storage/cache/slack';
            $filename = "{$path}/{$id}";
            
            // Cria a pasta caso não exista
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            
            // Verifica se tem o arquivo e caso n tenha cria
            if (!file_exists($filename)) {
                file_put_contents($filename, '');
            }
            
            // Limpa o cache do arquivo
            clearstatcache();
            
            return $filename;
        }
    }
}
