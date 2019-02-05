<?php
/**
 * Created by PhpStorm.
 * User: revan
 * Date: 30.01.2019
 * Time: 07:27
 */

namespace App\Repository;


use App\Http\Requests\StoreMenuRequest;
use App\Menu;
use Illuminate\Support\Carbon;


class MenuRepository
{

    /**
     * @param StoreMenuRequest $request
     *
     * @return Menu
     */
    public function createMenu(StoreMenuRequest $request) : Menu
    {
        $restaurant_id = $request->get('restaurant_id');

        /*Different logic depends on Restaurant*/
        switch ($restaurant_id) {
            case 1:
                $date = Carbon::now()->format('Y-m-d');
                break;
            default:
                $date = $request->get('date');
        }

        return Menu::create(['restaurant_id' => $restaurant_id, 'date' => $date]);
    }

    /**
     * @param array $dinners
     * @return array
     */
    public function getDinnersIds(array $dinners) : array
    {
        $dinnersIds = [];

        foreach ($dinners['dinners'] as $dinner) {
            $dinnersIds[] = $dinner['id'];
        }

        return $dinnersIds;
    }
}