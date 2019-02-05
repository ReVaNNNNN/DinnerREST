<?php

namespace App\Http\Controllers;


use App\Http\Requests\StoreMenuRequest;
use App\Menu;
use App\Repository\MenuRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
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

    /**
     * @param StoreMenuRequest $request
     * @return JsonResponse
     */
    public function store(MenuRepository $menuRepo, StoreMenuRequest $request) : JsonResponse
    {
        /** @var Menu $menu */
        $menu = $menuRepo->createMenu($request);
        $menu->dinners()->sync($menuRepo->getDinnersIds($request->only('dinners')));

        return response()->json(['status' => 'success', 'menu' => $menu], 201);
    }
}
