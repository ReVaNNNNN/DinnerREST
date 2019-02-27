<?php

namespace App\Http\Controllers;

use App\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Show all today orders
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        $orders = $this->getTodayOrders();

        return response()->json(['status' => 'success', 'orders' => $orders], 200);
    }

    //@todo Wydzielić do Traita
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
