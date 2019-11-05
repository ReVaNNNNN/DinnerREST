<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
     * @return BelongsToMany
     */
    public function dinners(): BelongsToMany
    {
        return $this->belongsToMany(Dinner::class, 'order_dinner');
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\User');
    }
}
