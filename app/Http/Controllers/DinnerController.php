<?php

namespace App\Http\Controllers;

use App\Dinner;
use App\Http\Requests\StoreDinnerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * @param StoreDinnerRequest $request
     * @return JsonResponse
     */
    public function store(StoreDinnerRequest $request)
    {
        /** @var Dinner $dinner */
        $dinner = Dinner::create($request->only('name', 'category_id', 'restaurant_id', 'price', 'photo'));
        // naprawić zapisywanie componentów do dinnerów
        // najpierw stworzyć te dinnery a potem powiązać bo nie ma jeszcze ich ID - poczytać o tym
        $this->addDinnerComponents($request->only('components'), $dinner->getId()); //przy updacie to samo

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

    /**
     * @param $components
     * @param int $dinnerId
     */
    private function addDinnerComponents($components, int $dinnerId)
    {

        // https://appdividend.com/2018/05/17/laravel-many-to-many-relationship-example/  <---- łaćzenie i usuwanie componentów z dinnerami
        foreach ($components as $componentId) {
            DB::table('component_dinner')->insert(['component_id' => $componentId, 'dinner_id' => $dinnerId]);
        }
    }
}
