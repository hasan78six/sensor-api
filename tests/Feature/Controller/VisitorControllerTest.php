<?php

use App\Models\Sensor;
use App\Models\Visitor;
use App\Models\Location;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use App\Services\VisitorService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Visitor Controller Test
 * 
 * This test suite verifies the functionality of the VisitorController.
 * It tests both successful and error scenarios for listing and creating visitors,
 * including filtering by date, validation, and duplicate entry handling.
 * 
 * @package Tests\Feature\Controller
 */
uses(RefreshDatabase::class);

/**
 * Set up the test environment before each test.
 * Creates a mock of the VisitorService, binds it to the container,
 * and creates test location and sensor for visitor associations.
 */
beforeEach(function () {
    $this->visitorService = Mockery::mock(VisitorService::class);
    $this->app->instance(VisitorService::class, $this->visitorService);
    $this->location = Location::factory()->create();
    $this->sensor = Sensor::factory()->create([
        'location_id' => $this->location->id
    ]);
});

/**
 * Test that the index endpoint returns an empty list when no visitors exist.
 * 
 * @test
 * @return void
 */
test('index returns empty visitors list when no visitors exist', function () {
    $this->visitorService
        ->shouldReceive('fetch')
        ->once()
        ->with(null)
        ->andReturn(collect([]));

    $this->getJson('/api/visitors')
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'success' => true,
            'message' => 'Success',
            'data' => []
        ]);
});

/**
 * Test that the index endpoint returns all visitors when they exist.
 * Verifies the correct formatting of visitor data including relationships.
 * 
 * @test
 * @return void
 */
test('index returns all visitors when they exist', function () {
    $visitors = collect([
        Visitor::factory()->create([
            'sensor_id' => $this->sensor->id,
            'date' => now()->format('Y-m-d')
        ]),
        Visitor::factory()->create([
            'sensor_id' => $this->sensor->id,
            'date' => now()->addDay()->format('Y-m-d')
        ]),
        Visitor::factory()->create([
            'sensor_id' => $this->sensor->id,
            'date' => now()->addDays(2)->format('Y-m-d')
        ])
    ]);

    $this->visitorService
        ->shouldReceive('fetch')
        ->once()
        ->with(null)
        ->andReturn($visitors);

    $this->getJson('/api/visitors')
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'success' => true,
            'message' => 'Success',
            'data' => $visitors->map(function ($visitor) {
                return [
                    'id' => $visitor->id,
                    'location_id' => $visitor->sensor->location_id,
                    'sensor_id' => $visitor->sensor_id,
                    'date' => $visitor->date->format('Y-m-d'),
                    'count' => $visitor->count,
                    'created_at' => $visitor->created_at->toJSON(),
                    'updated_at' => $visitor->updated_at->toJSON()
                ];
            })->toArray()
        ]);
});

/**
 * Test that the index endpoint returns filtered visitors when date is provided.
 * Verifies filtering functionality and response format for multiple sensors.
 * 
 * @test
 * @return void
 */
test('index returns filtered visitors when date is provided', function () {
    $date = now()->format('Y-m-d');
    $sensor2 = Sensor::factory()->create([
        'location_id' => $this->location->id
    ]);

    $visitors = collect([
        Visitor::factory()->create([
            'sensor_id' => $this->sensor->id,
            'date' => $date
        ]),
        Visitor::factory()->create([
            'sensor_id' => $sensor2->id,
            'date' => $date
        ])
    ]);

    $this->visitorService
        ->shouldReceive('fetch')
        ->once()
        ->with($date)
        ->andReturn($visitors);

    $this->getJson("/api/visitors?date={$date}")
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'success' => true,
            'message' => 'Success',
            'data' => $visitors->map(function ($visitor) {
                return [
                    'id' => $visitor->id,
                    'location_id' => $visitor->sensor->location_id,
                    'sensor_id' => $visitor->sensor_id,
                    'date' => $visitor->date->format('Y-m-d'),
                    'count' => $visitor->count,
                    'created_at' => $visitor->created_at->toJSON(),
                    'updated_at' => $visitor->updated_at->toJSON()
                ];
            })->toArray()
        ]);
});

/**
 * Test that the index endpoint returns validation error with invalid date format.
 * 
 * @test
 * @return void
 */
test('index returns validation error with invalid date format', function () {
    $this->getJson('/api/visitors?date=invalid-date')
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['date']);
});

/**
 * Test that the store endpoint successfully creates a new visitor.
 * Verifies the correct formatting of the created visitor data.
 * 
 * @test
 * @return void
 */
test('store creates a new visitor successfully', function () {
    $data = [
        'sensor_id' => $this->sensor->id,
        'date' => now()->format('Y-m-d'),
        'count' => 5
    ];

    $visitor = new Visitor($data);
    $visitor->id = (string) Str::uuid();
    $visitor->setRelation('sensor', $this->sensor);
    $visitor->created_at = now();
    $visitor->updated_at = now();

    $this->visitorService
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($visitor);

    $this->postJson('/api/visitors', $data)
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'success' => true,
            'message' => 'Visitor created',
            'data' => [
                'id' => $visitor->id,
                'location_id' => $visitor->sensor->location_id,
                'sensor_id' => $visitor->sensor_id,
                'date' => $visitor->date->format('Y-m-d'),
                'count' => $visitor->count,
                'created_at' => $visitor->created_at->toJSON(),
                'updated_at' => $visitor->updated_at->toJSON()
            ]
        ]);
});

/**
 * Test that the store endpoint returns validation error with invalid data.
 * Tests multiple validation rules including sensor_id, date, and count.
 * 
 * @test
 * @return void
 */
test('store returns validation error with invalid data', function () {
    $invalidData = [
        'sensor_id' => 'invalid-uuid',
        'date' => 'invalid-date',
        'count' => -1
    ];

    $this->postJson('/api/visitors', $invalidData)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['sensor_id', 'date', 'count']);
});

/**
 * Test that the store endpoint returns error when visitor already exists for sensor and date.
 * Verifies proper handling of unique constraint violations.
 * 
 * @test
 * @return void
 */
test('store returns validation error when visitor already exists for sensor and date', function () {
    $date = now()->format('Y-m-d');
    
    // Create an existing visitor
    Visitor::factory()->create([
        'sensor_id' => $this->sensor->id,
        'date' => $date,
        'count' => 5
    ]);

    // Try to create another visitor with same sensor and date
    $data = [
        'sensor_id' => $this->sensor->id,
        'date' => $date,
        'count' => 3
    ];

    $this->visitorService
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andThrow(new QueryException(
            'mysql',
            'insert into `visitors`',
            [],
            new PDOException('Duplicate entry for key \'visitors.sensor_id_date_unique\'')
        ));

    $this->postJson('/api/visitors', $data)
        ->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
        ->assertJson([
            'success' => false,
            'message' => 'Failed to create visitor',
            'errors' => 'Duplicate entry for key \'visitors.sensor_id_date_unique\' (Connection: mysql, SQL: insert into `visitors`)'
        ]);
});
