<?php

use Phinx\Seed\AbstractSeed;

/**
 * Class UsersSeeder
 *
 * @author Vagner Cardoso <vagnercardosoweb@gmail.com>
 */
class UsersSeeder extends AbstractSeed
{
    /**
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        // Prevent duplicates
        $this->table('users')->truncate();
        
        // Create users
        for ($i = 1; $i <= 10; $i++) {
            $user = new \App\Models\User();
            $user->data([
                'name' => "User {$i}",
                'email' => "user{$i}@email.com",
                'password' => $user->hash->make('password'),
                'status' => ['online', 'offline'][rand(0, 1)],
            ])->save();
        }
    }
}
