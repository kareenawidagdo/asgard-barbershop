<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

    // AKUN
Route::post('register', [AdminController::class, 'register']);
Route::post('login', [AdminController::class, 'login']);
Route::get('logout', [AdminController::class, 'logout']);
Route::put('password', [AdminController::class, 'change_password']);