<?php

namespace App\Http\Controllers;

use App\Dinner;
use App\Http\Requests\StoreDinnerRequest;
use App\Http\Requests\UpdateDinnerRequest;
use App\Repository\ComponentRepository;
use App\Repository\DinnerRepository;
use Illuminate\Http\JsonResponse;

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
        return response()->json(['status' => 'success', 'dinner' => $dinner->getWithComponents()], 200);
    }

    /**
     * @param ComponentRepository $componentRepo
     * @param DinnerRepository $dinnerRepo
     * @param StoreDinnerRequest $request
     *
     * @return JsonResponse
     */
    public function store(ComponentRepository $componentRepo, DinnerRepository $dinnerRepo,  StoreDinnerRequest $request) : JsonResponse
    {
        $dinnerData = $request->only('name', 'restaurant_id');

        if ($dinnerRepo->checkIfDinnerAlreadyExistForGivenRestaurant($dinnerData['name'], $dinnerData['restaurant_id'])) {
            return response()->json(['status' => 'error', 'message' => 'Dinner with given name already exists.'], 409);
        }

        $components = $componentRepo->findOrCreateNewComponents($request->only('components'));

        /** @var Dinner $dinner */
        $dinner = Dinner::create($request->only('name', 'category_id', 'restaurant_id', 'price', 'photo'));
        $dinner->components()->sync($componentRepo->getComponentsIds($components));

        return response()->json(['status' => 'success', 'dinner' => $dinner->getWithComponents()], 201);
    }


    /**
     * @param ComponentRepository $componentRepo
     * @param UpdateDinnerRequest $request
     * @param Dinner $dinner
     *
     * @return JsonResponse
     */
    public function update(ComponentRepository $componentRepo, UpdateDinnerRequest $request, Dinner $dinner) : JsonResponse
    {
        $dinner->update($request->except('components'));

        if ($data = $request->only('components')) {
            $components = $componentRepo->findOrCreateNewComponents($data);
            $dinner->components()->sync($componentRepo->getComponentsIds($components));
        }

        return response()->json(['status' => 'success', 'dinner' => $dinner->getWithComponents()], 200);
    }


    /**
     * @param Dinner $dinner
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Dinner $dinner) : JsonResponse
    {
        $dinner->components()->sync([]);
        $dinner->delete();

        return response()->json(['status' => 'success'], 200);
    }
}
