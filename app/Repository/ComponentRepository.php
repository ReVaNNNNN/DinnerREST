<?php

namespace App\Repository;


use App\Component;

class ComponentRepository
{
    /**
     * @param array $components
     * @return array
     */
    public function getOrCreateNewComponents(array $components)
    {
        $result = [];

        foreach ($components['components'] as $component) {
            $result[] = Component::where('name', $component['name'])->first() ?: Component::create($component);
        }

        return $result;
    }

    /**
     * @param array $components
     * @return array
     */
    public function getComponentsIds(array $components)
    {
        $componentsIds = [];

        foreach ($components as $component) {
            $componentsIds[] = $component->getId();
        }

        return $componentsIds;
    }
}
