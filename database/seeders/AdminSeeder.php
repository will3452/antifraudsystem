<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'administrator',
            'first_name' => 'super',
            'last_name' => 'admin',
            'address'=>'N/a',
            "email"=>"superadmin@af.com",
            "password"=>bcrypt("password"),
        ]);

        $user->assignRole(Role::ROLE_SUPER_ADMIN);

    }
}
