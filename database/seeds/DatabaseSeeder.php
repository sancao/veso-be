<?php

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Ramsey\Uuid\Uuid;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Entities\User::class,100)->create();
        App\Entities\User::create([
            'name' => 'Jane',
            'status'=>false,
            'username' => 'sangcao',
            'address'=>'Tay Ninh',
            'phone'=>'0917044714',
            'email' => 'a@a.com',
            'password' => bcrypt('sangcao'),
            'uuid'=>Uuid::uuid4()
        ]);
        
        factory(App\Entities\Daily::class,10)->create();
        factory(App\Entities\Chonso::class,50)->create();
        factory(App\Entities\Naptien::class,50)->create();
    }
}
