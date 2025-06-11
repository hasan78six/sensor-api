<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;

/**
 * Visitor Repository
 * 
 * Repository class for handling Visitor model database operations.
 * Extends the base repository to provide Visitor-specific data access methods
 * and summary statistics functionality.
 * 
 * @package App\Repositories
 */
class VisitorRepository extends BaseRepository
{
    /**
     * Create a new Visitor repository instance.
     * 
     * Initializes the repository with the Visitor model.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->model = new Visitor();
    }

    /**
     * Get summary statistics including visitor count and sensor status.
     * 
     * Retrieves aggregated data about visitors and sensor statuses from a given date.
     * The summary includes:
     * - Total number of visitors
     * - Count of active sensors
     * - Count of inactive sensors
     * 
     * @param Carbon $fromDate The start date for the summary statistics
     * @return object|null Object containing total_visitors, active_sensors, and inactive_sensors
     */
    public function getSummary(Carbon $fromDate)
    {
        return $this->model->query()
            ->select([
                DB::raw('SUM(visitors.count) as total_visitors'),
                DB::raw('COUNT(CASE WHEN sensors.status = "active" THEN 1 END) as active_sensors'),
                DB::raw('COUNT(CASE WHEN sensors.status = "inactive" THEN 1 END) as inactive_sensors')
            ])
            ->rightJoin('sensors', 'visitors.sensor_id', '=', 'sensors.id')
            ->where('visitors.date', '>=', $fromDate)
            ->first();
    }
}
