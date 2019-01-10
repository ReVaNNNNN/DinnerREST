<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = ['name', 'type', 'dinners'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dinners()
    {
        return $this->belongsToMany(Dinner::class);
    }
}
