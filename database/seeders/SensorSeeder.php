<?php

namespace Database\Seeders;

use App\Models\Sensor;
use Illuminate\Database\Seeder;

/**
 * Sensor Seeder
 * 
 * Seeds the database with initial sensor data for testing and development.
 * Uses the SensorFactory to generate consistent test data with random locations.
 * 
 * @package Database\Seeders
 */
class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates a set of sensors using the factory pattern.
     * The number of sensors and their attributes are defined in the factory.
     * Each sensor is automatically associated with a random location.
     *
     * @return void
     */
    public function run(): void
    {
        // Create 50 sensors, each assigned to a random location
        Sensor::factory(50)->create();
    }
}
