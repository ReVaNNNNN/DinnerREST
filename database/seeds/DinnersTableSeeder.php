<?php

use App\Dinner;
use Illuminate\Database\Seeder;

class DinnersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dinner::truncate();

        $faker = Faker\Factory::create();

        for ($i=0; $i<10; $i++) {
            Dinner::create([
                'name' => $faker->userName,
                'category_id' => $faker->numberBetween(0, 20),
                'restaurant_id' => $faker->numberBetween(0, 20),
                'price' => $faker->randomFloat(2, 0, 50),
                'photo' => NULL,
            ]);
        }
    }
}
