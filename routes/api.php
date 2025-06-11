<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\LocationController;

/**
 * API Routes
 * 
 * This file contains all the API routes for the application.
 * These routes are loaded by the RouteServiceProvider and all of them will
 * be assigned to the "api" middleware group.
 * 
 * @package App\Routes
 */

/**
 * Location Routes
 * 
 * @route GET /api/locations - List all locations
 * @route POST /api/locations - Create a new location
 */
Route::apiResource('locations', LocationController::class)->only(['index', 'store']);

/**
 * Sensor Routes
 * 
 * @route GET /api/sensors - List all sensors
 * @route POST /api/sensors - Create a new sensor
 */
Route::apiResource('sensors', SensorController::class)->only(['index', 'store']);

/**
 * Visitor Routes
 * 
 * @route GET /api/visitors - List all visitor records
 * @route POST /api/visitors - Create a new visitor record
 */
Route::apiResource('visitors', VisitorController::class)->only(['index', 'store']);

/**
 * Summary Routes
 * 
 * @route GET /api/summary - Get summary statistics
 */
Route::apiResource('summary', SummaryController::class)->only(['index']);
