<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 *
 * @property int $id
 * @property int $user_id
 */
class Order extends Model
{
    protected $fillable = ['user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function dinners()
    {
        return $this->belongsToMany(Dinner::class, 'order_dinner');
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }
}
