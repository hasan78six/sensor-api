<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Sensor Model
 * 
 * Represents a sensor entity in the system.
 * Uses UUID as primary key and is not auto-incrementing.
 * 
 * @property string $id The unique identifier of the sensor
 * @property string $name The name of the sensor
 * @property string $status The current status of the sensor (always stored in lowercase)
 * @property string $location_id The foreign key reference to the associated location
 * @property \Carbon\Carbon $created_at The timestamp when the record was created
 * @property \Carbon\Carbon $updated_at The timestamp when the record was last updated
 * 
 * @property-read Location $location The associated location
 * 
 * @package App\Models
 */
class Sensor extends Model
{
    use HasFactory;

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id',
        'name',
        'status',
        'location_id',
    ];

    /**
     * Mutator to ensure status is always stored in lowercase.
     *
     * @param string $value The status value to be set
     * @return void
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
    }

    /**
     * Get the location that owns the sensor.
     *
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
