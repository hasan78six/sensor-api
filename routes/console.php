<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/**
 * Console Routes
 * 
 * This file is where you may define all of your Closure based console commands.
 * Each Closure is bound to a command instance allowing a simple approach to
 * interacting with each command's IO methods.
 * 
 * @package App\Routes
 */

/**
 * Inspire Command
 * 
 * Displays an inspiring quote to the console.
 * This is a default Laravel command that can be used to test the console functionality.
 * 
 * @command inspire
 * @return void
 */
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
