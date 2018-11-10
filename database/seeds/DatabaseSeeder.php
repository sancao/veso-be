<?php

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        User::create([
            'name' => 'Jane',
            'email' => 'john@jane.com',
            'password' => bcrypt('password'),
            'user_uuid'=>Uuid::uuid4()
        ]);
    }
}
