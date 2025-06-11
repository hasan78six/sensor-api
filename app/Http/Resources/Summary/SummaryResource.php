<?php

namespace App\Http\Resources\Summary;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total_visitors_last_7_days' => (int) $this->total_visitors,
            'sensor_stats' => [
                'active' => (int) $this->active_sensors,
                'inactive' => (int) $this->inactive_sensors,
            ],
        ];
    }
}
