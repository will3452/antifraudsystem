<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedRole extends Migration
{
    public function createPermission($permission)
    {
        $array = [
            "view any $permission",
            "view $permission details",
            "create $permission",
            "delete $permission",
            "update $permission"
        ];

        foreach ($array as $item) {
            \App\Models\Permission::create([
                'group' => \Illuminate\Support\Str::plural($permission),
                'name' => $item
            ]);
        }
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = ['transaction', 'role', 'user', 'fee'];
        foreach ($permissions as $permission) {
            $this->createPermission($permission);
        }
    }
}
