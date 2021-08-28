<?php

use App\Http\Controllers\CacheController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\RainController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cache', [CacheController::class, 'index']);

Route::get('/log', [LogController::class, 'index']);

Route::get('/rain', [RainController::class, 'index']);
