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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $date = $request->get('date') ?? Carbon::today();
        $orders = Order::with(['users', 'dinners'])
            ->where('created_at', '=', $date->format('Y-m-d'))
            ->get();

        return response()->json(['status' => 'success', 'orders' => $orders], 200); // sprawdzić czy to działa
    }

    public function store(StoreOrderRequest $request)
    {
        /** @var Order $order */
        $order = Order::create();
        $order->users()->sync($request->only('user_id'));
        $order->dinners()->sync($request->only('dinners')); // z arajki dinnerów wyciągnąć tylko ajdiki

        return response()->json(['status' => 'success', 'order' => $order], 201); // zwrócić order z dinnerami i userem
    }
}
