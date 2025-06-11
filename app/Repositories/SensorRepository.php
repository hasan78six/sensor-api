<?php

namespace App\Repositories;

use App\Models\Sensor;
use Illuminate\Support\Collection;

/**
 * Sensor Repository
 * 
 * Repository class for handling Sensor model database operations.
 * Extends the base repository to provide Sensor-specific data access methods.
 * 
 * @package App\Repositories
 */
class SensorRepository extends BaseRepository
{
    /**
     * Create a new Sensor repository instance.
     * 
     * Initializes the repository with the Sensor model.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->model = new Sensor();
    }
}