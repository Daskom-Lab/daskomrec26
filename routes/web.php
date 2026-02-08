<?php

use App\Http\Controllers\StageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\PlottinganController;
use App\Http\Controllers\CaasstageController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::resource('shifts', ShiftController::class)->except(['index']);

Route::resource('users', UserController::class)->except(['index']);

Route::get('/', function () {
    return inertia('welcome');
});

Route::get('/login', function () {
    return inertia('User/login');
});

Route::get('/user/', function () {
    return inertia('User/home');
});

Route::get('/user/home', function () {
    return inertia('User/home');
});

Route::get('/user/profile', function () {
    return inertia('User/profile');
});

Route::get('/user/password', function () {
    return inertia('User/password');
});

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

Route::get('/admin/login', function () {
    return inertia('Admin/login');
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

Route::get('/admin/password', function () {
    return inertia('Admin/password');
});

Route::get('/admin/caas', [UserController::class, 'index']);
Route::post('/admin/caas', [UserController::class, 'store']);

Route::put('/admin/caas/{caasstage}/stage', [CaasstageController::class, 'updateStage']);
Route::put('/admin/caas/{caasstage}/status', [CaasstageController::class, 'updateStatus']);
