<?php

use App\Http\Controllers\Api\ApartmentController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\LoginController;

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

// * ApartmentController
Route::apiResource('apartments', ApartmentController::class);

// SponsoredApartments
Route::get('sponsored-apartments', [ApartmentController::class, 'sponsoredApartments'])->name('sponsored-apartments');

// * MessageController
Route::post('messages', [MessageController::class, 'store']);

// * ServiceController
Route::apiResource('services', ServiceController::class);

// * LoginController
Route::get('/login', [LoginController::class, 'login'])->name('api.login');