<?php

use App\Models\Sensor;
use App\Models\Location;
use Illuminate\Http\Response;
use App\Services\SensorService;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Sensor Controller Test
 * 
 * This test suite verifies the functionality of the SensorController.
 * It tests both successful and error scenarios for listing and creating sensors,
 * including filtering, pagination, and validation cases.
 * 
 * @package Tests\Feature\Controller
 */
uses(RefreshDatabase::class);

/**
 * Set up the test environment before each test.
 * Creates a mock of the SensorService, binds it to the container,
 * and creates a test location for sensor associations.
 */
beforeEach(function () {
    $this->sensorService = Mockery::mock(SensorService::class);
    $this->app->instance(SensorService::class, $this->sensorService);
    $this->location = Location::factory()->create();
});

/**
 * Test that the index endpoint returns an empty list when no sensors exist.
 * 
 * @test
 * @return void
 */
test('index returns empty sensors list when no sensors exist', function () {
    $this->sensorService
        ->shouldReceive('fetch')
        ->once()
        ->with(null, null)
        ->andReturn(collect([]));

    $this->getJson('/api/sensors')
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'success' => true,
            'message' => 'Success',
            'data' => []
        ]);
});

/**
 * Test that the index endpoint returns all sensors when they exist.
 * 
 * @test
 * @return void
 */
test('index returns all sensors when they exist', function () {
    $sensors = Sensor::factory()
        ->count(3)
        ->create(['location_id' => $this->location->id]);

    $this->sensorService
        ->shouldReceive('fetch')
        ->once()
        ->with(null, null)
        ->andReturn($sensors);

    $this->getJson('/api/sensors')
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'success' => true,
            'data' => $sensors->toArray()
        ]);
});

/**
 * Test that the index endpoint returns filtered sensors when status is provided.
 * 
 * @test
 * @return void
 */
test('index returns filtered sensors when status is provided', function () {
    $sensors = Sensor::factory()
        ->count(2)
        ->create([
            'location_id' => $this->location->id,
            'status' => 'active'
        ]);

    $this->sensorService
        ->shouldReceive('fetch')
        ->once()
        ->with('active', null)
        ->andReturn($sensors);

    $this->getJson('/api/sensors?status=active')
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'success' => true,
            'data' => $sensors->toArray()
        ]);
});

/**
 * Test that the index endpoint returns limited sensors when limit is provided.
 * 
 * @test
 * @return void
 */
test('index returns limited sensors when limit is provided', function () {
    $sensors = Sensor::factory()
        ->count(1)
        ->create(['location_id' => $this->location->id]);

    $this->sensorService
        ->shouldReceive('fetch')
        ->once()
        ->with(null, 1)
        ->andReturn($sensors);

    $response = $this->getJson('/api/sensors?limit=1');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'success' => true,
            'data' => $sensors->toArray()
        ]);
});

/**
 * Test that the index endpoint returns validation error with invalid status.
 * 
 * @test
 * @return void
 */
test('index returns validation error with invalid status', function () {
    $response = $this->getJson('/api/sensors?status=invalid');

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['status']);
});

/**
 * Test that the index endpoint returns validation error with invalid limit.
 * 
 * @test
 * @return void
 */
test('index returns validation error with invalid limit', function () {
    $response = $this->getJson('/api/sensors?limit=0');

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['limit']);
});

/**
 * Test that the store endpoint successfully creates a new sensor.
 * 
 * @test
 * @return void
 */
test('store creates a new sensor successfully', function () {
    $data = [
        'status' => 'active',
        'location_id' => $this->location->id,
        'name' => 'Test Sensor'
    ];

    $sensor = Sensor::factory()->make($data);

    $this->sensorService
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($sensor);

    $this->postJson('/api/sensors', $data)
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'success' => true,
            'message' => 'Sensor created',
            'data' => $sensor->toArray()
        ]);
});

/**
 * Test that the store endpoint returns validation error with invalid data.
 * Tests multiple validation rules including status, location_id, and name.
 * 
 * @test
 * @return void
 */
test('store returns validation error with invalid data', function () {
    $invalidData = [
        'status' => 'invalid',
        'location_id' => 'invalid-uuid',
        'name' => ''
    ];

    $this->postJson('/api/sensors', $invalidData)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['status', 'location_id', 'name']);
});

/**
 * Test that the store endpoint returns validation error when name is taken for location.
 * Verifies the unique validation rule for sensor names within the same location.
 * 
 * @test
 * @return void
 */
test('store returns validation error when name is taken for location', function () {
    Sensor::factory()->create([
        'location_id' => $this->location->id,
        'name' => 'Test Sensor'
    ]);

    $data = [
        'status' => 'active',
        'location_id' => $this->location->id,
        'name' => 'Test Sensor'
    ];

    $this->postJson('/api/sensors', $data)
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['name']);
});

/**
 * Test that the store endpoint returns error when service throws exception.
 * Verifies proper error handling and response format for service exceptions.
 * 
 * @test
 * @return void
 */
test('store returns error when service throws exception', function () {
    $data = [
        'status' => 'active',
        'location_id' => $this->location->id,
        'name' => 'Test Sensor'
    ];

    $this->sensorService
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andThrow(new Exception('Database connection failed'));

    $response = $this->postJson('/api/sensors', $data);

    $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
        ->assertJson([
            'success' => false,
            'message' => 'Failed to create sensor',
            'errors' => 'Database connection failed'
        ]);
});
