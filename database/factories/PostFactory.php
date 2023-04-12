<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
         'country' => $this->faker->text(5),
         'province' => $this->faker->text(6),
         'city' => $this->faker->text(7),
         'address' => $this->faker->text(15),
         'title' => $this->faker->text(20),
         'description' => $this->faker->paragraph(), 
         'position' => DB::raw("(ST_GeomFromText('POINT(54.8765696 -2.9261824)'))"), 
         'confirmed' => true,
         'available' => true

        ];
    }
}
