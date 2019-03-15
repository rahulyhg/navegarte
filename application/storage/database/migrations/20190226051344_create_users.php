<?php

/**
 * VCWeb Networks <https://www.vagnercardosoweb.com.br/>
 *
 * @package   VCWeb Networks
 * @author    Vagner Cardoso <vagnercardosoweb@gmail.com>
 * @license   MIT
 *
 * @copyright Vagner Cardoso
 */

use Core\Contracts\Migration;

/**
 * Class CreateUsers
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class CreateUsers extends Migration
{
    /**
     * @var string
     */
    protected $table = 'users';
    
    /**
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * @return void
     * @throws \Exception
     *
     * @see http://docs.phinx.org/en/latest/migrations.html
     */
    public function up()
    {
        $this->table($this->table)
            ->addColumn('name', 'string', ['limit' => 150])
            ->addColumn('email', 'string', ['limit' => 150])
            ->addColumn('password', 'string', ['limit' => 200])
            ->addTimestamps()
            ->addColumn('status', 'enum', [
                'values' => ['online', 'offline'],
                'default' => 'online',
            ])
            ->addIndex('email', ['unique' => true, 'name' => "{$this->table}_email_uindex"])
            ->save();
    }
    
    /**
     * @return void
     * @throws \Exception
     */
    public function down()
    {
        parent::down();
    }
}
