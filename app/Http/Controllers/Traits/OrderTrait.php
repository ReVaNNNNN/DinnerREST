<?php
/**
 * Created by PhpStorm.
 * User: revan
 * Date: 01.03.2019
 * Time: 07:00
 */

namespace App\Http\Controllers\Traits;


use App\Order;
use Carbon\Carbon;

trait OrderTrait
{
    /**
     * @param int|null $userId
     * @return Order|Order[]|null
     */
    private function getTodayOrders(int $userId = null)
    {
        $order = Order::with('dinners')
            ->whereDate('created_at', '=', Carbon::today()->format('Y-m-d'));


        return $userId ? $order->where('user_id', '=', $userId)->first() : $order->get();
    }
}