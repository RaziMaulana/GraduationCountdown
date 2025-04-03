<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\OverviewController;
use App\Http\Controllers\admin\TimeSettingController;
use App\Http\Controllers\Graduation\GraduationTimeController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('Graduation')->group(function () {
    Route::get('/', [GraduationTimeController::class, 'GraduationTimeIndex'])->middleware(['auth', 'verified'])->name('graduation.index');
    Route::get('/countdown', [GraduationTimeController::class, 'getCountdown'])->middleware(['auth', 'verified'])->name('graduation.countdown');
});

Route::prefix('admin')->group(function () {
    Route::get('/overview', [OverviewController::class, 'OverviewIndex'])->middleware(['auth', 'verified'])->name('admin.overview');
    Route::post('/overview/store', [OverviewController::class, 'OverviewStore'])->middleware(['auth', 'verified'])->name('admin.overview.store');
    Route::get('/overview/{id}', [OverviewController::class, 'OverviewShow'])->name('admin.overview.show');
    Route::get('/overview/{id}/edit', [OverviewController::class, 'OverviewEdit'])->name('admin.overview.edit');
    Route::put('/overview/{id}', [OverviewController::class, 'OverviewUpdate'])->name('admin.overview.update');
    Route::delete('/overview/{id}', [OverviewController::class, 'OverviewDestroy'])->name('admin.overview.destroy');

    Route::get('/time-setting', [TimeSettingController::class, 'TimeSettingIndex'])->middleware(['auth', 'verified'])->name('admin.time-setting');
    Route::get('/time-setting/countdown', [TimeSettingController::class, 'getCountdown'])->middleware(['auth', 'verified'])->name('admin.time-setting.countdown');
    Route::post('/time-setting/countdown', [TimeSettingController::class, 'setCountdown'])->middleware(['auth', 'verified'])->name('admin.time-setting.countdown');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
