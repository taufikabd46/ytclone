<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VideoController; 
use App\Http\Controllers\SettingController;



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

//prefix auth
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('register', [AuthController::class, 'register']);
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->middleware('auth:api');
    Route::post('/', [CategoryController::class, 'store'])->middleware('auth:api');
    Route::get('/{id}', [CategoryController::class, 'show'])->middleware('auth:api');
    Route::put('/{id}', [CategoryController::class, 'update'])->middleware('auth:api');
    Route::delete('/{id}', [CategoryController::class, 'destroy'])->middleware('auth:api');
    //videos within a category
    Route::get('/{id}/videos', [CategoryController::class, 'videos'])->middleware('auth:api');
}); 

Route::prefix('videos')->group(function () {
    Route::get('/', [VideoController::class, 'index'])->middleware('auth:api');
    Route::post('/', [VideoController::class, 'store'])->middleware('auth:api');
    Route::get('/{id}', [VideoController::class, 'show'])->middleware('auth:api');
    Route::put('/{id}', [VideoController::class, 'update'])->middleware('auth:api');
    Route::delete('/{id}', [VideoController::class, 'destroy'])->middleware('auth:api');
    //get one video, get video, but not random, there is no id or something, not random, not randomize. not /random
    Route::get('/getVideo', [VideoController::class, 'getVideo']);
    //get random video, get video, but random, there is no id or something, random, randomize. not /random
    Route::get('/random', [VideoController::class, 'getRandomVideo']);
    //updateOrder
    Route::post('/update-order', [VideoController::class, 'updateOrder'])->middleware('auth:api');
    

});

Route::prefix('settings')->group(function () {
    Route::get('/', [SettingController::class, 'index']);
    Route::put('/randomize', [SettingController::class, 'changeRandomize'])->middleware('auth:api');
});

Route::get('/random', [VideoController::class, 'getRandomVideo']);
Route::get('/getVideo', [VideoController::class, 'getVideo']);
Route::get('/settings', [SettingController::class, 'index']);
Route::get('/getaVideo/{id}', [VideoController::class, 'show']);


//update video order
//Route::get('/update-order', [VideoController::class, 'updateVideoOrder']);