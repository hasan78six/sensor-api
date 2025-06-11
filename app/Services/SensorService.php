<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use App\Repositories\SensorRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Sensor Service
 * 
 * Service class for handling Sensor-related business logic.
 * Manages sensor data retrieval, creation, and caching operations.
 * 
 * @package App\Services
 */
class SensorService
{
    /**
     * The Sensor repository instance.
     *
     * @var SensorRepository
     */
    protected SensorRepository $repository;

    /**
     * Create a new Sensor service instance.
     * 
     * @param SensorRepository $repository The Sensor repository instance
     * @return void
     */
    public function __construct(SensorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Fetch sensors with optional filtering and pagination.
     * 
     * @param string|null $status Filter sensors by status
     * @param int|null $limit Number of records per page
     * @return Collection|LengthAwarePaginator
     * @throws Exception When repository operation fails
     */
    public function fetch(?string $status = null, ?int $limit = null)
    {
        try {
            $where = $status ? ['status' => $status] : [];
            $page = $limit ? request()->get('page', 1) : null;
            
            $cacheKey = 'status:' . ($status ?? 'all') . ':limit:' . ($limit ?? 'all');
            if ($page) {
                $cacheKey .= ':page:' . $page;
            }

            return Cache::tags(['sensors'])->remember(
                $cacheKey, 
                (int) env('CACHE_TTL', 600), 
                fn() => $this->repository->get([], $where, [], $limit)
            );
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a new sensor.
     * 
     * @param array $data The sensor data to create
     * @return \App\Models\Sensor
     * @throws Exception When repository operation fails
     */
    public function create(array $data)
    {
        try {
            $result = $this->repository->create($data);
            
            Cache::tags(['sensors', 'summary'])->flush();
            
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }
}