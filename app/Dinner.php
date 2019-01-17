<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Dinner
 * @property int id
 */

class Dinner extends Model
{
    protected $fillable = ['name', 'category_id', 'restaurant_id', 'price', 'photo', 'components'];

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function components()
    {
        return $this->belongsToMany(Component::class, 'component_dinner');
    }
}
