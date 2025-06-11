<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Response;
use App\Services\SummaryService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Summary\SummaryResource;

/**
 * Summary Controller
 * 
 * Handles summary statistics related HTTP requests.
 * 
 * @package App\Http\Controllers
 */
class SummaryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param SummaryService $service The summary service instance
     */
    public function __construct(
        protected SummaryService $service
    ) {}

    /**
     * Display summary statistics.
     *
     * @return JsonResponse Returns a JSON response containing:
     *                      - Success: Summary statistics data
     *                      - Error: Error message with HTTP 500 status code
     * @throws Exception When there's an error fetching summary statistics
     */
    public function index(): JsonResponse
    {
        try {
            return ApiResponse::success(
                new SummaryResource($this->service->get())
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to fetch summary statistics',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }
    }
}
