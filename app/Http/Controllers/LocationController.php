<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Services\LocationService;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\Location\StoreLocationRequest;

/**
 * Location Controller
 * 
 * Handles all location-related HTTP requests including listing and creating locations.
 * 
 * @package App\Http\Controllers
 */
class LocationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param LocationService $service The location service instance
     */
    public function __construct(
        protected LocationService $service
    ) {}

    /**
     * Display a listing of locations.
     *
     * @return JsonResponse Returns a JSON response containing:
     *                      - Success: Collection of locations or empty array
     *                      - Error: Error message with HTTP 500 status code
     * @throws Exception When there's an error fetching locations
     */
    public function index(): JsonResponse
    {
        try {
            $locations = $this->service->fetch();
            return ApiResponse::success(
                $locations,
                $locations->isEmpty() ? 'No locations found' : 'Success'
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to fetch locations',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }
    }

    /**
     * Store a new location in the database.
     *
     * @param StoreLocationRequest $request The validated request containing location data
     * @return JsonResponse Returns a JSON response containing:
     *                      - Success: Created location data with HTTP 201 status code
     *                      - Error: Error message with HTTP 500 status code
     * @throws Exception When there's an error creating the location
     */
    public function store(StoreLocationRequest $request): JsonResponse
    {
        try {
            $location = $this->service->create($request->validated());
            return ApiResponse::success($location, 'Location created', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to create location',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }
    }
}
