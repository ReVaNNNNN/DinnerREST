<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Order
 *
 * @property int $id
 */
class Order extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_order');
    }

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
