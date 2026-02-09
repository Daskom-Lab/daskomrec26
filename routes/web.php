<?php

use App\Http\Controllers\StageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\PasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('welcome');
});

Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'index'])-> name('login');

//route store login
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'store']);

Route::middleware('auth')->group(function (){
    //route logout
    Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'destroy'])->middleware('auth');

    Route::get('/User/home', function () {
        return inertia('User/home');
    });

    Route::get('/user/profile', [ProfileController::class, 'index'])->name('Profile.index');
    Route::post('/user/profile', [ProfileController::class, 'update'])->name('Profile.update');

    // Route::get('/user/password', function () {
    //     return inertia('User/password');
    // });

    Route::get('/user/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/user/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/user/assistants', function () {
        return inertia('User/assistants');
    });

    Route::get('/user/oaline', function () {
        return inertia('User/oaline');
    });

    Route::get('/user/shift', function () {
        return inertia('User/shift');
    });

    Route::get('/user/announcement', function () {
        return inertia('User/announcement');
    });

    Route::get('/user/cores', function () {
        return inertia('User/cores');
    });
});

Route::prefix('admin')->group(function (){
    Route::get('login', function () {
        return inertia('Admin/login');
    });
    
    Route::get('home', function () {
        return inertia('Admin/home');
    });
    
    Route::get('shift', function () {
        return inertia('Admin/shift');
    });
    
    Route::get('password', function () {
        return inertia('Admin/password');
    });
    
    Route::get('caas', function () {
        return inertia('Admin/caas');
    });
});




