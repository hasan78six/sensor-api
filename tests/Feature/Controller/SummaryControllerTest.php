<?php

use Illuminate\Http\Response;
use App\Services\SummaryService;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Summary Controller Test
 * 
 * This test suite verifies the functionality of the SummaryController.
 * It tests both successful and error scenarios for retrieving summary statistics,
 * including visitor counts and sensor status information.
 * 
 * @package Tests\Feature\Controller
 */
uses(RefreshDatabase::class);

/**
 * Set up the test environment before each test.
 * Creates a mock of the SummaryService and binds it to the container.
 */
beforeEach(function () {
    $this->summaryService = Mockery::mock(SummaryService::class);
    $this->app->instance(SummaryService::class, $this->summaryService);
});

/**
 * Test that the index endpoint successfully returns summary statistics.
 * Verifies the correct formatting of visitor counts and sensor status data.
 * 
 * @test
 * @return void
 */
test('index returns summary statistics successfully', function () {
    $summaryData = [
        'total_visitors' => 150,
        'active_sensors' => 10,
        'inactive_sensors' => 2
    ];

    $this->summaryService
        ->shouldReceive('get')
        ->once()
        ->andReturn((object) $summaryData);

    $this->getJson('/api/summary')
        ->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'success' => true,
            'data' => [
                'total_visitors_last_7_days' => 150,
                'sensor_stats' => [
                    'active' => 10,
                    'inactive' => 2
                ]
            ]
        ]);
});

/**
 * Test that the index endpoint returns error when service throws exception.
 * Verifies proper error handling and response format for service exceptions.
 * 
 * @test
 * @return void
 */
test('index returns error when service throws exception', function () {
    $this->summaryService
        ->shouldReceive('get')
        ->once()
        ->andThrow(new Exception('Database connection failed'));

    $this->getJson('/api/summary')
        ->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
        ->assertJson([
            'success' => false,
            'message' => 'Failed to fetch summary statistics',
            'errors' => 'Database connection failed'
        ]);
});
