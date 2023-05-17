<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Laboratory>
 */
class LaboratoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'RoomNo' => fake()->numberBetween(10, 20),
            'AisleNo' => str_pad(fake()->numberBetween(1, 5) * 2, 2, '0', STR_PAD_LEFT),
            'FloorNo' => '04',
            'BuildingCode' => 'LB',
            'Capacity' => fake()->numberBetween(30, 50)
        ];
    }
}
