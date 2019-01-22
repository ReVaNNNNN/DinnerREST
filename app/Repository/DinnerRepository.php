<?php

namespace App\Repository;


use App\Dinner;

class DinnerRepository
{
    /**
     * @param string $dinnerName
     * @param int $restaurantId
     * @return bool
     */
    public function checkIfDinnerAlreadyExistForGivenRestaurant(string $dinnerName, int $restaurantId) : bool
    {
        return (bool) Dinner::where('name', $dinnerName)->where('restaurant_id', $restaurantId)->first();
    }
}
