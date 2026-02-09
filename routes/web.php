<?php

use App\Http\Controllers\StageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\PlottinganController;
use App\Http\Controllers\CaasstageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\PasswordController;
use Illuminate\Support\Facades\Route;

Route::resource('shifts', ShiftController::class)->except(['index']);

Route::resource('users', UserController::class)->except(['index']);

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

Route::get('/admin/home', [HomeController::class, 'index']);
Route::put('/admin/home/current-stage', [HomeController::class, 'setCurrentStage']);
Route::put('/admin/home/configuration/{stage}', [HomeController::class, 'updateConfiguration']);
Route::put('/admin/home/messages/{stage}', [HomeController::class, 'updateStageMessages']);

Route::get('/admin/shift', [ShiftController::class, 'index']);

Route::get('/admin/plottingan', [PlottinganController::class, 'index']);
Route::get('/admin/plottingan/shift/{shiftId}', [PlottinganController::class, 'shiftUsers']);

Route::get('/admin/configuration', [StageController::class, 'index']);
Route::put('/admin/configuration/{stage}', [StageController::class, 'update']);


Route::get('/admin/caas', [UserController::class, 'index']);
Route::post('/admin/caas', [UserController::class, 'store']);

Route::put('/admin/caas/{caasstage}/stage', [CaasstageController::class, 'updateStage']);
Route::put('/admin/caas/{caasstage}/status', [CaasstageController::class, 'updateStatus']);
