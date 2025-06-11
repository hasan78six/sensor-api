<?php

use App\Models\Location;
use Illuminate\Http\Response;
use App\Services\LocationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Location Controller Test
 * 
 * This test suite verifies the functionality of the LocationController.
 * It tests both successful and error scenarios for listing and creating locations.
 * 
 * @package Tests\Feature\Controller
 */
uses(RefreshDatabase::class);

/**
 * Set up the test environment before each test.
 * Creates a mock of the LocationService and binds it to the container.
 */
beforeEach(function () {
    $this->locationService = Mockery::mock(LocationService::class);
    $this->app->instance(LocationService::class, $this->locationService);
});

/**
 * Test that the index endpoint returns an empty list when no locations exist.
 * 
 * @test
 * @return void
 */
test('index returns empty locations list when no locations exist', function () {
    $this->locationService
        ->shouldReceive('fetch')
        ->once()
        ->andReturn(collect([]));

    $response = $this->getJson('/api/locations');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'success' => true,
            'message' => 'No locations found',
            'data' => []
        ]);
});

/**
 * Test that the index endpoint returns all locations when they exist.
 * 
 * @test
 * @return void
 */
test('index returns all locations when they exist', function () {
    $locations = Location::factory()->count(3)->create();

    $this->locationService
        ->shouldReceive('fetch')
        ->once()
        ->andReturn($locations);

    $response = $this->getJson('/api/locations');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'success' => true,
            'data' => $locations->toArray()
        ]);
});

/**
 * Test that the store endpoint successfully creates a new location.
 * 
 * @test
 * @return void
 */
test('store creates a new location successfully', function () {
    $data = [
        'name' => 'Test Location'
    ];

    $location = Location::factory()->make($data);

    $this->locationService
        ->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($location);

    $response = $this->postJson('/api/locations', $data);

    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson([
            'success' => true,
            'message' => 'Location created',
            'data' => $location->toArray()
        ]);
});

/**
 * Test that the store endpoint returns validation errors with invalid data.
 * Specifically tests the unique validation for location names.
 * 
 * @test
 * @return void
 */
test('store returns validation error with invalid data', function () {
    // Create a location first to test unique validation
    Location::factory()->create(['name' => 'Existing Location']);

    $invalidData = [
        'name' => 'Existing Location' // This should fail unique validation
    ];

    $response = $this->postJson('/api/locations', $invalidData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['name'])
        ->assertJson([
            'errors' => [
                'name' => [
                    'The name has already been taken.'
                ]
            ]
        ]);
});
