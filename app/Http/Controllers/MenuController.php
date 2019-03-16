<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Traits\MenuTrait;
use App\Http\Requests\StoreMenuRequest;
use App\Menu;
use App\Repository\MenuRepository;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    use MenuTrait;

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
