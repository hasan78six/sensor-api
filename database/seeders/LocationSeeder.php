<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

/**
 * Location Seeder
 * 
 * Seeds the database with initial location data for testing and development.
 * Uses the LocationFactory to generate consistent test data.
 * 
 * @package Database\Seeders
 */
class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates a set of locations using the factory pattern.
     * The number of locations and their attributes are defined in the factory.
     *
     * @return void
     */
    public function run(): void
    {
        Location::factory()->count(20)->create();
    }
}
