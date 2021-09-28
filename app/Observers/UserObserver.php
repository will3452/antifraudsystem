<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user){
        $firstName = $user['first_name'];
        $lastName = $user['last_name'];
        return  $user['name'] = "$firstName $lastName";
    }
}
