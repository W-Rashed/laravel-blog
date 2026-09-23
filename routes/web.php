<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;

/**
 * Web Routes for LaravelBlog
 */

// Blog Feed and Single Post
Route::get('/', [BlogController::class, 'index'])->name('blog.index');
Route::get('/post/{id}', [BlogController::class, 'show'])->name('blog.show');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
