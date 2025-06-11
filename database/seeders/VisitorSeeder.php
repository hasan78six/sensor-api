<?php

namespace Database\Seeders;

use App\Models\Sensor;
use App\Models\Visitor;
use Illuminate\Database\Seeder;

/**
 * Visitor Seeder
 * 
 * Seeds the database with initial visitor data for testing and development.
 * Creates visitor records with random counts for each sensor-date combination.
 * Uses the VisitorFactory to generate consistent test data.
 * 
 * @package Database\Seeders
 */
class VisitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates visitor records by:
     * 1. Generating all possible sensor-date combinations
     * 2. Randomly selecting a subset of these combinations
     * 3. Creating visitor records for each selected combination
     * 
     * The number of records and their attributes are defined in the factory.
     * Each record is associated with a specific sensor and date.
     *
     * @return void
     */
    public function run(): void
    {
        $recordsToGenerate = 50;

        $sensors = Sensor::pluck('id');
        $dates = collect(range(1, now()->daysInMonth()))
            ->map(fn($d) => now()->startOfMonth()->addDays($d - 1)->format('Y-m-d'));

        $pairs = $sensors->crossJoin($dates)->shuffle()->take($recordsToGenerate);

        foreach ($pairs as [$sensorId, $date]) {
            Visitor::factory()->create([
                'sensor_id' => $sensorId,
                'date' => $date,
            ]);
        }
    }
}
