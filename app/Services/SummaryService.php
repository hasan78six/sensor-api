<?php

namespace App\Services;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Repositories\VisitorRepository;

/**
 * Summary Service
 * 
 * Service class for handling summary statistics and analytics.
 * Manages visitor count and sensor status data retrieval with caching.
 * 
 * @package App\Services
 */
class SummaryService
{
    /**
     * The Visitor repository instance.
     *
     * @var VisitorRepository
     */
    protected VisitorRepository $repository;

    /**
     * Create a new Summary service instance.
     * 
     * @param VisitorRepository $repository The Visitor repository instance
     * @return void
     */
    public function __construct(VisitorRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get summary statistics including visitor count and sensor status.
     * Retrieves data for the last 7 days and caches the results.
     *
     * @return object Summary statistics object containing visitor count and sensor status
     * @throws Exception When repository operation fails
     */
    public function get(): object
    {
        try {
            $sevenDaysAgo = Carbon::now()->subDays(7);

            return Cache::tags(['summary'])->remember(
                'summary:last_7_days',
                (int) env('CACHE_TTL', 600),
                fn() => $this->repository->getSummary($sevenDaysAgo)
            );
        } catch (Exception $e) {
            throw $e;
        }
    }
}
