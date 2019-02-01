<?php

namespace App\Repository;


use App\Component;

class ComponentRepository
{

    /**
     * @param array $components
     *
     * @return array
     * @throws \Exception
     */
    public function findOrCreateNewComponents(array $components) : array
    {
        $result = [];

        foreach ($components['components'] as $component) {
            $result[] = Component::where('name', $component['name'])->first() ?: $this->createNewComponent($component);
        }

        return $result;
    }

    /**
     * @param array $components
     * @return array
     */
    public function getComponentsIds(array $components) : array
    {
        $componentsIds = [];

        foreach ($components as $component) {
            $componentsIds[] = $component->getId();
        }

        return $componentsIds;
    }

    /**
     * @param array $component
     *
     * @return Component
     * @throws \Exception
     */
    public function createNewComponent(array $component)
    {
        if (!in_array($component['type'], Component::ALLOWED_TYPES)) {
            throw new \Exception('Not allowed component type: ' . $component['type']);
        }

        return Component::create($component);
    }
}
