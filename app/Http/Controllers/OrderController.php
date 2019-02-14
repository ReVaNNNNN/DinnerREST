<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
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
        $orders = Order::with('dinners')
            ->whereDate('created_at', '=', Carbon::today()->format('Y-m-d'))
            ->get();

        return response()->json(['status' => 'success', 'orders' => $orders], 200);
    }

    /**
     * Show today's user order
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function userOrder(int $userId) : JsonResponse
    {
        $order = Order::with('dinners')
            ->where('user_id', '=', $userId)
            ->whereDate('created_at', '=', Carbon::today()->format('Y-m-d'))
            ->first();

        return response()->json(['status' => 'success', 'order' => $order], 200);
    }

    /**
     * @param StoreOrderRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request) : JsonResponse
    {
        /** @var Order $order */
        $order = Order::create($request->only('user_id'));
        $order->dinners()->sync($request->only('dinners')['dinners']);

        $orderInfo = Order::with('dinners')->find($order->getId());

        return response()->json(['status' => 'success', 'order' => $orderInfo], 201);
    }
}