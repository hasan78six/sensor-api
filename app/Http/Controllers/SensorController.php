<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Response;
use App\Services\SensorService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Sensor\SensorResource;
use App\Http\Requests\Sensor\SensorIndexRequest;
use App\Http\Requests\Sensor\StoreSensorRequest;

/**
 * Sensor Controller
 * 
 * Handles all sensor-related HTTP requests including listing and creating sensors.
 * 
 * @package App\Http\Controllers
 */
class SensorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param SensorService $service The sensor service instance
     */
    public function __construct(
        protected SensorService $service
    ) {}

    /**
     * Display a listing of sensors.
     *
     * @param SensorIndexRequest $request The validated request containing filter parameters
     * @return JsonResponse Returns a JSON response containing:
     *                      - Success: Collection of sensors or single sensor resource
     *                      - Error: Error message with HTTP 500 status code
     * @throws Exception When there's an error fetching sensors
     */
    public function index(SensorIndexRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $sensors = $this->service->fetch(
                $validated['status'] ?? null,
                $validated['limit'] ?? null
            );

            if ($sensors->isEmpty()) {
                return ApiResponse::success(
                    [],
                    'Success'
                );
            }

            return ApiResponse::success(
                isset($validated['limit']) 
                    ? $sensors->toArray()
                    : SensorResource::collection($sensors)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to fetch sensors',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }
    }

    /**
     * Store a new sensor in the database.
     *
     * @param StoreSensorRequest $request The validated request containing sensor data
     * @return JsonResponse Returns a JSON response containing:
     *                      - Success: Created sensor data with HTTP 201 status code
     *                      - Error: Error message with HTTP 500 status code
     * @throws Exception When there's an error creating the sensor
     */
    public function store(StoreSensorRequest $request): JsonResponse
    {
        try {
            $sensor = $this->service->create($request->validated());

            return ApiResponse::success(new SensorResource($sensor), 'Sensor created', Response::HTTP_CREATED);
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to create sensor',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }
    }
}
