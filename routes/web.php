<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\VideoController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/videos/getVideo', [VideoController::class, 'getVideo']);
//get random video, get video, but random, there is no id or something, random, randomize. not /random
Route::get('/videos/random', [VideoController::class, 'getRandomVideo']);
Route::get('/videos', [SettingController::class, 'index']);