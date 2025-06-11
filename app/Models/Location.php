<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Location Model
 * 
 * Represents a location entity in the system.
 * Uses UUID as primary key and is not auto-incrementing.
 * 
 * @property string $id The unique identifier of the location
 * @property string $name The name of the location
 * @property \Carbon\Carbon $created_at The timestamp when the record was created
 * @property \Carbon\Carbon $updated_at The timestamp when the record was last updated
 * 
 * @package App\Models
 */
class Location extends Model
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
    ];
}
