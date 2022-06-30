<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CreateUserController;
use App\Http\Controllers\FileExportController;
use App\Http\Controllers\HomeController;

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/api', [ApiController::class, 'store'])->name('api.store');

Route::get('/management', [CreateUserController::class, 'showManagementPage'])->name('management');
Route::post('/updateUserId', [CreateUserController::class, 'updateUserId'])->name('management.updateUserId');
Route::post('/createUser', [CreateUserController::class, 'createUser'])->name('management.createUser');
Route::post('/createManualStartEndTime', [CreateUserController::class, 'createManualStartEndTime'])->name('management.createManualStartEndTime');


Route::get('/export', [FileExportController::class, 'download'])->name('export');

