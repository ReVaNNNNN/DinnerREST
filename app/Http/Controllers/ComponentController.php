<?php

namespace App\Http\Controllers;

use App\Component;
use App\Http\Requests\StoreComponentRequest;
use Illuminate\Http\Request;

class ComponentController extends Controller
{

    /**
     * @return Component[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Component::all();
    }

    /**
     * @param Component $component
     * @return Component
     */
    public function show(Component $component)
    {
        return $component;
    }

    /**
     * @param StoreComponentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreComponentRequest $request)
    {
        $component = Component::create($request->only('name', 'type'));

        return response()->json($component, 201);
    }

    /**
     * @param Request $request
     * @param Component $component
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Component $component)
    {
        $component->update($request->all());

        return response()->json($component, 200);
    }


    /**
     * @param Component $component
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Component $component)
    {
        $component->delete();

        return response()->json(null, 204);
    }
}
