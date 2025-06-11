<?php

namespace App\Http\Resources\Sensor;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\JsonResource;

class SensorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof LengthAwarePaginator) {
            return [
                'data' => SensorResource::collection($this->resource->items()),
                'meta' => [
                    'current_page' => $this->resource->currentPage(),
                    'from' => $this->resource->firstItem(),
                    'to' => $this->resource->lastItem(),
                    'per_page' => $this->resource->perPage(),
                    'total' => $this->resource->total(),
                    'last_page' => $this->resource->lastPage(),
                    'prev_page_url' => $this->resource->previousPageUrl(),
                    'next_page_url' => $this->resource->nextPageUrl(),
                ]
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'location_id' => $this->location_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
