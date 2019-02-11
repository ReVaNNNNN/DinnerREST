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
    public function index(Request $request) : JsonResponse
    {
        $date = $request->get('date') ?? Carbon::today();
        $orders = Order::with(['users', 'dinners'])
            ->whereDate('created_at', '=', $date->format('Y-m-d'))
            ->get();

        return response()->json(['status' => 'success', 'orders' => $orders], 200);
    }

    /**
     * Show today user order
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function userOrder(Request $request) : JsonResponse
    {
        // wez z zalogowane usera id i dodaj do warunku
        $date = $request->get('date') ?? Carbon::today();
        $orders = Order::with(['users', 'dinners'])
            ->whereDate('created_at', '=', $date->format('Y-m-d'))
            ->get();

        return response()->json(['status' => 'success', 'orders' => $orders], 200); // sprawdzić czy to działa
    }

    /**
     * @param StoreOrderRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request) : JsonResponse
    {
        /** @var Order $order */
        $order = Order::create();
        $order->users()->sync($request->only('user_id'));
        $order->dinners()->sync($request->only('dinners')); // z arajki dinnerów wyciągnąć tylko ajdiki

        $orderInfo = Order::with(['users', 'dinners'])->find($order->getId());

        return response()->json(['status' => 'success', 'order' => $orderInfo], 201);
    }
}
