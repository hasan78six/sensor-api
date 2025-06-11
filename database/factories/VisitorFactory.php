<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Sensor;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Visitor Factory
 * 
 * Factory for generating test data for the Visitor model.
 * Creates visitor count records with random dates from the current month
 * and associates them with random sensors.
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visitor>
 */
class VisitorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{
     *     id: string,
     *     sensor_id: string|null,
     *     date: string,
     *     count: int
     * } The default state of the model
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'sensor_id' => Sensor::inRandomOrder()->first()?->id,
            'date' => $this->generateRandomDate(),
            'count' => $this->faker->numberBetween(1, 1000),
        ];
    }

    /**
     * Generate a random date from the current month.
     *
     * @return string Date in Y-m-d format
     */
    private function generateRandomDate(): string
    {
        return $this->faker->dateTimeBetween(
            now()->startOfMonth(),
            now()->endOfMonth()
        )->format('Y-m-d');
    }
}
