<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Visitor Model
 * 
 * Represents a visitor count record in the system.
 * Uses UUID as primary key and is not auto-incrementing.
 * 
 * @property string $id The unique identifier of the visitor record
 * @property string $sensor_id The foreign key reference to the associated sensor
 * @property \Carbon\Carbon $date The date of the visitor count
 * @property int $count The number of visitors counted
 * @property \Carbon\Carbon $created_at The timestamp when the record was created
 * @property \Carbon\Carbon $updated_at The timestamp when the record was last updated
 * 
 * @property-read Sensor $sensor The associated sensor
 * 
 * @package App\Models
 */
class Visitor extends Model
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
        'sensor_id',
        'date',
        'count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'count' => 'integer',
    ];

    /**
     * Get the sensor that owns the visitor count.
     *
     * @return BelongsTo
     */
    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }
}
