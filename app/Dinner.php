<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dinner extends Model
{
    protected $fillable = ['name', 'category_id', 'restaurant_id', 'price', 'photo'];
}
