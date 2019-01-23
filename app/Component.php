<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Component
 * @property int id
 */
class Component extends Model
{
    protected $fillable = ['name', 'type', 'dinners'];

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
    public function dinners()
    {
        return $this->belongsToMany(Dinner::class, 'component_dinner');
    }
}
