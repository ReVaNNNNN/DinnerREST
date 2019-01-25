<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 * @property int id
 */
class Menu extends Model
{
    protected $fillable = ['restaurant_id', 'date'];

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    public function dinners()
    {
        return $this->belongsToMany(Dinner::class, 'dinner_menu');
    }
}
