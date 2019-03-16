<?php
/**
 * Created by PhpStorm.
 * User: revan
 * Date: 16.03.2019
 * Time: 09:05
 */

namespace App\Http\Controllers\Traits;


use App\Menu;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\JsonResponse;

trait MenuTrait
{
    /**
     * Show today's menu
     * @return JsonResponse
     */
    public function show() : JsonResponse
    {
        $menu = Menu::with('dinners')
            ->where('date','>=',Carbon::now()->startOfDay())
            ->where('date','<=',Carbon::now()->endOfDay())
            ->first();

        return response()->json(['status' => 'success', 'menu' => $menu], 200);
    }
}