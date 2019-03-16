<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Traits\OrderTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use OrderTrait;

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

}
