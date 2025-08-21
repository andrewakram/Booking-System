<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Admin\DashboardController;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\Provider\ServiceController;
use App\Http\Controllers\V1\Customer\ServiceController as CustomerServiceController;
use App\Http\Controllers\V1\Provider\LocationController;
use App\Http\Controllers\V1\Customer\BookingController;
use App\Http\Controllers\V1\Provider\BookingController as ProviderBookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('v1/login', [AuthController::class, 'login'])->name('login');

//admin
Route::prefix('v1/admin')->middleware(['auth:sanctum'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'statitics']);

})->middleware(['role:admin']);

//provider
Route::prefix('v1/provider')->middleware(['auth:sanctum'])->group(function () {

    Route::post('add-multi-location', [LocationController::class, 'addMultiLocation']);
    Route::post('add-multi-service', [ServiceController::class, 'addMultiService']);

    Route::post('update-status/{bookingId}', [ProviderBookingController::class, 'updateStatus']);


})->middleware(['role:provider']);

//customer
Route::prefix('v1/customer')->middleware(['auth:sanctum'])->group(function () {

    Route::get('show-published-services', [CustomerServiceController::class, 'showPublishedServices']);
    Route::get('get-availability', [BookingController::class, 'getAvailability']);
    Route::post('book', [BookingController::class, 'book']);

})->middleware(['role:customer']);
