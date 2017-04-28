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

namespace App\Models;

use Navegarte\Contracts\BaseModel;

/**
 * Class News
 *
 * @package App\Models
 * @author  Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
final class News extends BaseModel
{
  /**
   * Nome da tabela
   *
   * @var string
   */
  protected $table = 'noticias';
}
