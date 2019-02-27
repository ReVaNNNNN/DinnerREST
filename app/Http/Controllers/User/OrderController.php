<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Order;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Show today's user order
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function show(int $userId) : JsonResponse
    {
        $order = $this->getTodayOrders($userId);

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

    /**
     * @param Order $order
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Order $order) : JsonResponse
    {
        $user = Auth::user();

        if ($user->can('destroy', $order)) {
            $order->dinners()->sync([]);
            $order->delete();

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error', 'message' => 'An unauthorized user for this action'], 403);
    }

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
