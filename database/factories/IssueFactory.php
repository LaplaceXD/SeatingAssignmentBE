<?php

namespace Database\Factories;

use App\Enums\IssueStatus;
use App\Models\Issue;
use Faker\Core\DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    protected $model = Issue::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Title' => fake()->sentence(3, false),
            'SeatNo' => strtoupper(chr(rand(0, 5) + ord('a'))) . fake()->randomDigit(),
            'Description' => fake('en')->paragraph(2),
            'ReplicationSteps' => function (array $attributes) {
                $steps = rand(1, 5);

                $replicationSteps = '';
                for ($i = 1; $i <= $steps; $i++) $replicationSteps .= $i . ') ' . fake('en')->paragraph(1) . '\n';

                return $replicationSteps;
            },
            'CompletedAt' => function (array $attributes) {
                $isCompleted = in_array($attributes['Status'], IssueStatus::completedCases());

                return $isCompleted ? fake()->dateTimeBetween('-30 days') : null;
            },
            'ValidatedAt' => function (array $attributes) {
                $max = $attributes['CompletedAt']?->format('Y-m-d H:i:s') ?? 'now';

                return array_key_exists('ValidatorID', $attributes) && $attributes['ValidatorID']
                    ? fake()->dateTimeBetween('-45 days', $max) : null;
            },
            'IssuedAt' => function (array $attributes) {
                $validatedAt = $attributes['ValidatedAt'];
                $max = $validatedAt?->format('Y-m-d H:i:s') ?? 'now';

                return fake()->dateTimeBetween('-60 days', $max);
            }
        ];
    }
}
