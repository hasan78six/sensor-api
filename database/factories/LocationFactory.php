<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Location Factory
 * 
 * Factory for generating test data for the Location model.
 * Generates sequential mall names (Mall A, Mall B, ..., Mall Z, Mall AA, etc.).
 * 
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Static counter for generating sequential mall names.
     *
     * @var int
     */
    private static int $index = 0;

    /**
     * Define the model's default state.
     *
     * @return array{id: string, name: string} The default state of the model
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => $this->generateMallName(),
        ];
    }

    /**
     * Generate a sequential mall name.
     *
     * @return string The generated mall name
     */
    private function generateMallName(): string
    {
        $letters = '';
        $i = self::$index++;
        
        do {
            $letters = chr(65 + ($i % 26)) . $letters;
            $i = intdiv($i, 26) - 1;
        } while ($i >= 0);

        return 'Mall ' . $letters;
    }
}
