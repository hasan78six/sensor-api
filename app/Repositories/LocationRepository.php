<?php

namespace App\Repositories;

use App\Models\Location;

/**
 * Location Repository
 * 
 * Repository class for handling Location model database operations.
 * Extends the base repository to provide Location-specific data access methods.
 * 
 * @package App\Repositories
 */
class LocationRepository extends BaseRepository
{
    /**
     * Create a new Location repository instance.
     * 
     * Initializes the repository with the Location model.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->model = new Location();
    }
}