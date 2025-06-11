<?php

namespace App\Services;

use Exception;
use App\Repositories\LocationRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * Location Service
 * 
 * Service class for handling Location-related business logic.
 * Acts as an intermediary between controllers and the Location repository,
 * providing a layer for business logic and error handling.
 * 
 * @package App\Services
 */
class LocationService
{
    /**
     * The Location repository instance.
     *
     * @var LocationRepository
     */
    protected LocationRepository $repository;

    /**
     * Create a new Location service instance.
     * 
     * @param LocationRepository $repository The Location repository instance
     * @return void
     */
    public function __construct(LocationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Fetch all locations.
     * 
     * Retrieves all locations from the repository with error handling.
     * 
     * @return Collection
     * @throws Exception When repository operation fails
     */
    public function fetch()
    {
        try {
            return $this->repository->get();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a new location.
     * 
     * Creates a new location record with the provided data.
     * 
     * @param array $data The location data to create
     * @return \App\Models\Location
     * @throws Exception When repository operation fails
     */
    public function create(array $data)
    {
        try {
            return $this->repository->create($data);
        } catch (Exception $e) {
            throw $e;
        }
    }
}