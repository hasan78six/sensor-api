<?php

namespace App\Http\Controllers;

use Exception;
use App\Helpers\ApiResponse;
use Illuminate\Http\Response;
use App\Services\VisitorService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Visitor\VisitorResource;
use App\Http\Requests\Visitor\StoreVisitorRequest;
use App\Http\Requests\Visitor\VisitorIndexRequest;

/**
 * Visitor Controller
 * 
 * Handles all visitor-related HTTP requests including listing and creating visitors.
 * 
 * @package App\Http\Controllers
 */
class VisitorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param VisitorService $service The visitor service instance
     */
    public function __construct(
        protected VisitorService $service
    ) {}

    /**
     * Display a listing of visitors.
     *
     * @param VisitorIndexRequest $request The validated request containing filter parameters
     * @return JsonResponse Returns a JSON response containing:
     *                      - Success: Collection of visitors
     *                      - Error: Error message with HTTP 500 status code
     * @throws Exception When there's an error fetching visitors
     */
    public function index(VisitorIndexRequest $request): JsonResponse
    {
        try {
            $visitors = $this->service->fetch($request->validated()['date'] ?? null);

            if ($visitors->isEmpty()) {
                return ApiResponse::success(
                    [],
                    'Success'
                );
            }

            return ApiResponse::success(
                VisitorResource::collection($visitors)
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to fetch visitors',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }
    }

    /**
     * Store a new visitor in the database.
     *
     * @param StoreVisitorRequest $request The validated request containing visitor data
     * @return JsonResponse Returns a JSON response containing:
     *                      - Success: Created visitor data with HTTP 201 status code
     *                      - Error: Error message with HTTP 500 status code
     * @throws Exception When there's an error creating the visitor
     */
    public function store(StoreVisitorRequest $request): JsonResponse
    {
        try {
            return ApiResponse::success(
                new VisitorResource($this->service->create($request->validated())),
                'Visitor created',
                Response::HTTP_CREATED
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                'Failed to create visitor',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $e->getMessage()
            );
        }
    }
}
