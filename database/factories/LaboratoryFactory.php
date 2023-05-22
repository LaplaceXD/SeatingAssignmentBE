<?php

namespace Database\Factories;

use App\Models\Laboratory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Laboratory>
 */
class LaboratoryFactory extends Factory
{
    protected $model = Laboratory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'LabName' => 'Computer Laboratory',
            'RoomNo' => fake()->randomDigitNotZero(),
            'AisleNo' => fake()->randomDigitNotZero(),
            'FloorNo' => 4,
            'BuildingCode' => 'LB',
            'Capacity' => fake()->numberBetween(30, 50)
        ];
    }
}
