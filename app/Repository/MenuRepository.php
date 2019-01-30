<?php
/**
 * Created by PhpStorm.
 * User: revan
 * Date: 30.01.2019
 * Time: 07:27
 */

namespace App\Repository;


class MenuRepository
{
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