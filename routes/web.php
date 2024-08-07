<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
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
    return redirect()->route('login');
});

Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'login'])->name('login');
    Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');
    Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');
});

Route::prefix('register')->group(function () {
    Route::get('/', [RegisterController::class, 'register'])->name('register');
    Route::post('actionregister', [RegisterController::class, 'actionregister'])->name('actionregister');
    Route::get('editregister', [RegisterController::class, 'editregister'])->name('editregister');
    Route::post('updateregister', [RegisterController::class, 'updateregister'])->name('updateregister');
    Route::get('listregister', [RegisterController::class, 'listregister'])->name('listregister');
    Route::get('filter_register', [RegisterController::class, 'filter_register'])->name('filter_register');
});

Route::prefix('home')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
});


// admin123
