<?php

namespace App\Http\Controllers;

use App\Component;
use App\Http\Requests\StoreComponentRequest;
use App\Http\Requests\UpdateComponentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ComponentController extends Controller
{


    /**
     * @return JsonResponse
     */
    public function index() : JsonResponse
    {
        $components = Component::all();

        return response()->json(['status' => 'success', 'components' => $components], 200);
    }


    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id) : JsonResponse
    {
        $component = Component::find($id);

        return response()->json(['status' => 'success', 'component' => $component], 200);
    }


    /**
     * @param UpdateComponentRequest $request
     * @param Component $component
     *
     * @return JsonResponse
     */
    public function update(UpdateComponentRequest $request, Component $component) : JsonResponse
    {
        $component->update($request->all());

        return response()->json(['status' => 'success', 'components' => $component], 200);
    }


    /**
     * @param Component $component
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Component $component) : JsonResponse
    {
        $component->dinners()->sync([]);
        $component->delete();

        return response()->json(['status' => 'success'], 200);
    }
}
