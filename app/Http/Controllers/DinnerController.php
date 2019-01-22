<?php

namespace App\Http\Controllers;

use App\Dinner;
use App\Http\Requests\StoreDinnerRequest;
use App\Repository\ComponentRepository;
use App\Repository\DinnerRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DinnerController extends Controller
{

    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        $dinners = Dinner::with('components')->get();

        return response()->json(['status' => 'success', 'dinners' => $dinners], 200);
    }


    /**
     * @param Dinner $dinner
     *
     * @return JsonResponse
     */
    public function show(Dinner $dinner) : JsonResponse
    {
        $dinner = Dinner::with('components')->find($dinner->getId());

        return response()->json(['status' => 'success', 'dinner' => $dinner], 200);
    }

    /**
     * @param ComponentRepository $componentRepo
     * @param DinnerRepository $dinnerRepo
     * @param StoreDinnerRequest $request
     * @return JsonResponse
     */
    public function store(ComponentRepository $componentRepo, DinnerRepository $dinnerRepo,  StoreDinnerRequest $request) : JsonResponse
    {
        $dinnerData = $request->only('name', 'restaurant_id');

        if ($dinnerRepo->checkIfDinnerAlreadyExistForGivenRestaurant($dinnerData['name'], $dinnerData['restaurant_id'])) {
            return response()->json(['status' => 'error', 'message' => 'Dinner with given name already exists.'], 409);
        }

        $components = $componentRepo->findOrCreateNewComponents($request->only('components'));

        $dinner = Dinner::create($request->only('name', 'category_id', 'restaurant_id', 'price', 'photo'));
        $dinner->components()->sync($componentRepo->getComponentsIds($components), false);

        return response()->json(['status' => 'success', 'dinner' => $dinner], 201);
    }

    /**
     * @param Request $request
     * @param Dinner $dinner
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Dinner $dinner)
    {
        $dinner->update($request->all());

        return response()->json($dinner, 200);
    }


    /**
     * @param Dinner $dinner
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Dinner $dinner)
    {
        $dinner->delete();

        return response()->json(null, 204);
    }
}
