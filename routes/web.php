<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OperationController;

Route::middleware('web')->group(function () {
    
    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::get('/login', function () {
        return view('login');
    })->name('login')->middleware('guest');
    
    Route::get('/register', function () {
        return view('register');
    })->name('register')->middleware('guest');
    
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard-data', [DashboardController::class, 'data']);
        Route::get('/history', [OperationController::class, 'index'])->name('history');
        Route::get('/history-data', [OperationController::class, 'data'])->name('history.data');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

});


Route::prefix('api')->middleware('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function () {
            return auth()->user();
        });      
        
    });
});