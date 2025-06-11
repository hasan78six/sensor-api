<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Sensor Factory
 * 
 * Factory for generating test data for the Sensor model.
 * Creates sensors with unique names, random status, and associates them with random locations.
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sensor>
 */
class SensorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{
     *     id: string,
     *     name: string,
     *     status: 'active'|'inactive',
     *     location_id: string|null
     * } The default state of the model
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => 'Sensor ' . $this->faker->unique()->numerify('##'),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'location_id' => Location::inRandomOrder()->first()?->id,
        ];
    }
}