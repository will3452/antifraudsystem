<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends \Spatie\Permission\Models\Role
{
    const ROLE_SUPER_ADMIN = "super admin";

    protected $appends = ['prepared_permissions'];

    public function getPreparedPermissionsAttribute()
    {
        return $this->permissions->pluck('name')->toArray();
    }
}
