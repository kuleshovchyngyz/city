<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Handle preflight requests for all API routes
Route::options('/{any}', function () {
    return response()->noContent(200);
})->where('any', '.*');

// Apply CORS middleware to all API routes
Route::middleware(['cors'])->group(function () {
    Route::post('/autocomplete-ajax-city', [\App\Http\Controllers\GroupController::class, 'dataAjaxCity'])->name('apiCity');
    Route::get('/search', [\App\Http\Controllers\GroupController::class, 'searchCity'])->name('apiSearch');
    Route::post('/getcities', [\App\Http\Controllers\NewCityController::class, 'getcities'])->name('getcities');
    Route::get('/get', [\App\Http\Controllers\NewCityController::class, 'searchAskar'])->name('apiAskar');
    Route::get('/city/{city}', [\App\Http\Controllers\GroupController::class, 'showCity'])->name('apiShow');
    Route::get('/groups', [\App\Http\Controllers\NewGroupController::class, 'index']);
    Route::get('/groups/{id}', [\App\Http\Controllers\NewGroupController::class, 'show']);
});

// Auth routes
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
