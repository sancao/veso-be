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
        // factory(App\Entities\Daily::class,5)->create();
        // factory(App\Entities\User::class,20)->create();
        App\Entities\User::create([
            'name' => 'Jane',
            'status'=>false,
            'daily_id'=>0,
            'username' => 'sangcao',
            'address'=>'Tay Ninh',
            'phone'=>'0917044714',
            'quyen'=>'admin',
            'email' => 'a@a.com',
            "client_id"=>'123',
            'password' => bcrypt('sangcao'),
            'uuid'=>Uuid::uuid4()
        ]);

        App\Entities\User::create([
            'name' => 'đại lý',
            'status'=>false,
            'daily_id'=>0,
            'username' => 'daily',
            'address'=>'Tay Ninh',
            'phone'=>'0917089714',
            'quyen'=>'daily',
            'email' => 'b@a.com',
            "client_id"=>'456',
            'password' => bcrypt('sangcao'),
            'uuid'=>Uuid::uuid4()
        ]);

        App\Entities\User::create([
            'name' => 'nhân viên',
            'status'=>false,
            'daily_id'=>0,
            'username' => 'nhanvien',
            'address'=>'Tay Ninh',
            'phone'=>'0914589714',
            'quyen'=>'nhanvien',
            'email' => 'c@a.com',
            "client_id"=>'789',
            'password' => bcrypt('sangcao'),
            'uuid'=>Uuid::uuid4()
        ]);

        // tạo danh sách quyền cho user

        // admin
        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"user/list",
        ]);
        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"user/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"user/edit",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"chonso/list",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"chonso/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"chonso/delete",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"giaoso/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"giaoso/delete",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"giaoso/list",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"nhapso/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"nhapso/list",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"nhapso/delete",
        ]);


        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"daily/list",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"daily/list-all",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"daily/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"daily/edit",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'admin',
            'route'=>"daily/delete",
        ]);


        //daily
        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"user/list",
        ]);
        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"user/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"user/edit",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"chonso/list",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"chonso/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"chonso/delete",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"giaoso/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"giaoso/delete",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"giaoso/list",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"nhapso/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"nhapso/list",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'daily',
            'route'=>"nhapso/delete",
        ]);

        //nhân viên

        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"user/list",
        ]);
        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"user/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"user/edit",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"chonso/list",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"chonso/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"chonso/delete",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"giaoso/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"giaoso/delete",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"giaoso/list",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"nhapso/add",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"nhapso/list",
        ]);

        App\Entities\RoleModule::create([
            'quyen' => 'nhanvien',
            'route'=>"nhapso/delete",
        ]);

        
        // factory(App\Entities\Chonso::class,5)->create();
        // factory(App\Entities\Naptien::class,5)->create();
    }
}
