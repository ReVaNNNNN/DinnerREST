<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const ADMIN = 1;
    const USER = 2;
    const RESTAURANT_EMPLOYEE = 3;
    const GUEST = 4;
}
