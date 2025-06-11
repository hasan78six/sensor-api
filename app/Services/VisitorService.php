<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use App\Repositories\VisitorRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Visitor Service
 * 
 * Service class for handling visitor-related business logic.
 * Manages visitor data retrieval, creation, and caching operations with access counting.
 * 
 * @package App\Services
 */
class VisitorService
{
    /**
     * The Visitor repository instance.
     *
     * @var VisitorRepository
     */
    protected VisitorRepository $repository;

    /**
     * Create a new Visitor service instance.
     * 
     * @param VisitorRepository $repository The Visitor repository instance
     * @return void
     */
    public function __construct(VisitorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Fetch visitors with optional date filtering and access counting.
     * Implements a caching strategy based on access frequency.
     * 
     * @param string|null $date Filter visitors by specific date
     * @return Collection|LengthAwarePaginator
     * @throws Exception When repository operation fails
     */
    public function fetch(?string $date = null)
    {
        try {
            $where = $date ? ['date' => $date] : [];
            $cacheKey = 'date:' . ($date ?? 'all');
            $counterKey = "visitors:access_count:$cacheKey";

            $count = Cache::store('redis')->increment($counterKey);

            if ($count === 1) {
                Cache::store('redis')->put($counterKey, 1, (int) env('CACHE_COUNTER_TTL', 86400));
            }

            if ($count >= (int) env('CACHE_COUNTER_THRESHOLD', 10)) {
                return Cache::tags(['visitors'])->remember(
                    $cacheKey,
                    (int) env('CACHE_TTL', 600),
                    fn() => $this->repository->get([], $where, ['sensor:id,location_id'])
                );
            }

            return $this->repository->get([], $where, ['sensor:id,location_id']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a new visitor record.
     * Invalidates relevant cache entries after creation.
     * 
     * @param array $data The visitor data to create
     * @return \App\Models\Visitor
     * @throws Exception When repository operation fails
     */
    public function create(array $data)
    {
        try {
            $result = $this->repository->create($data);

            foreach (['date:' . $data['date'], 'date:all'] as $key) {
                if (Cache::tags(['visitors'])->has($key)) {
                    Cache::tags(['visitors'])->forget($key);
                }
            }

            Cache::tags(['summary'])->flush();

            $result->load('sensor:id,location_id');

            return $result;
        } catch (Exception $e) {
            report($e);
            throw $e;
        }
    }
}
