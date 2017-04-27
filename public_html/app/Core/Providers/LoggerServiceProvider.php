<?php

/**
 * NAVEGARTE Networks
 *
 * @package   FrontEnd
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright 2017-2017 Vagner Cardoso - NAVEGARTE
 */

namespace App\Core\Providers;

use App\Core\Contracts\BaseServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Slim\Container;

/**
 * Class LoggerServiceProvider
 *
 * @package App\Core\Providers
 */
final class LoggerServiceProvider extends BaseServiceProvider
{
  /**
   * Registers services on the given container.
   *
   * @param \Slim\Container $container
   *
   * @return mixed|void
   */
  public function register(Container $container)
  {
    /**
     * @return \Closure
     */
    $container['logger'] = function () {
      /**
       * @param string|null $file
       *
       * @return \Monolog\Logger
       */
      return function ($file = null) {
        /**
         * Instance logger
         */
        $logger = new Logger(config('app.name', 'log'));
        
        /**
         * Verify name/dir
         */
        if (is_null($file)) {
          $file = 'log.' . substr(md5(date('Ymd')), 0, 10) . '.log';
        } else {
          $file = $file . '.' . substr(md5(date('Ymd')), 0, 10) . '.log';
        }
        
        /**
         * Salved log dir
         */
        $dirOutput = ROOT . "/storage/logs/{$file}";
        
        /**
         * Custom formatter logger
         */
        $dateFormat = 'd/m/Y H:i:s';
        $format = "[%datetime%] %level_name%: \r\n%message% \n%context%\r\n\n";
        $formatter = new LineFormatter($format, $dateFormat);
        
        /**
         * Create logger handler
         */
        $logger->pushHandler((new StreamHandler($dirOutput, Logger::DEBUG))->setFormatter($formatter));
        
        return $logger;
      };
    };
  }
}
