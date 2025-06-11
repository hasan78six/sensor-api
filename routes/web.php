<?php

use Illuminate\Support\Facades\Route;

/**
 * Web Routes
 * 
 * This file contains all the routes that will be loaded by the RouteServiceProvider
 * and all of them will be assigned to the "web" middleware group.
 * 
 * @package App\Routes
 */

/**
 * Welcome Page Route
 * 
 * Displays the welcome page of the application.
 * This is typically the landing page for new visitors.
 * 
 * @return \Illuminate\View\View
 */
Route::get('/', function () {
    return view('welcome');
});

