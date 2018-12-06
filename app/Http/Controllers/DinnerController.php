<?php

namespace App\Http\Controllers;

use App\Dinner;
use App\Http\Requests\StoreDinnerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Array_;

class DinnerController extends Controller
{
    /**
     * @return Dinner[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Dinner::all();
    }

    /**
     * @param Dinner $dinner
     * @return Dinner
     */
    public function show(Dinner $dinner)
    {
        return $dinner;
    }

    /**
     * @param StoreDinnerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDinnerRequest $request)
    {
        /** @var Dinner $dinner */
        $dinner = Dinner::create($request->only('name', 'category_id', 'restaurant_id', 'price'));
        $this->addDinnerComponents($request->only('components'), $dinner->getId()); //przy updacie to samo

        return response()->json($dinner, 201);
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
     * @param array $components
     * @param int $dinnerId
     */
    private function addDinnerComponents(array $components, int $dinnerId)
    {
        foreach ($components as $component) {
            DB::table('component_dinner')->insert(['component_id' => $component['id'], 'dinner_id' => $dinnerId]);
        }
    }
}
