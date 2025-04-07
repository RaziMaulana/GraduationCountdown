<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\OverviewController;
use App\Http\Controllers\admin\TimeSettingController;
use App\Http\Controllers\Graduation\ResultController;
use App\Http\Controllers\Graduation\GraduationTimeController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('kelulusan')->group(function () {
    Route::get('/', [GraduationTimeController::class, 'GraduationTimeIndex'])->middleware(['auth', 'verified'])->name('kelulusan.index');
    Route::get('/countdown', [GraduationTimeController::class, 'getCountdown'])->middleware(['auth', 'verified'])->name('kelulusan.countdown');

    Route::get('/hasil/{status?}', [ResultController::class, 'ResultIndex'])->middleware(['auth', 'verified'])->name('kelulusan.hasil');
});

Route::prefix('admin')->group(function () {
    Route::get('/manajemen-data', [OverviewController::class, 'OverviewIndex'])->middleware(['auth', 'verified'])->name('admin.manajemen-data');
    Route::post('/manajemen-data/store', [OverviewController::class, 'OverviewStore'])->middleware(['auth', 'verified'])->name('admin.manajemen-data.store');
    Route::get('/manajemen-data/{id}', [OverviewController::class, 'OverviewShow'])->name('admin.manajemen-data.show');
    Route::get('/manajemen-data/{id}/edit', [OverviewController::class, 'OverviewEdit'])->name('admin.manajemen-data.edit');
    Route::put('/manajemen-data/{id}', [OverviewController::class, 'OverviewUpdate'])->name('admin.manajemen-data.update');
    Route::delete('/manajemen-data/{id}', [OverviewController::class, 'OverviewDestroy'])->name('admin.manajemen-data.destroy');

    Route::get('/atur-waktu', [TimeSettingController::class, 'TimeSettingIndex'])->middleware(['auth', 'verified'])->name('admin.atur-waktu');
    Route::get('/atur-waktu/countdown', [TimeSettingController::class, 'getCountdown'])->middleware(['auth', 'verified'])->name('admin.atur-waktu.countdown');
    Route::post('/atur-waktu/countdown', [TimeSettingController::class, 'setCountdown'])->middleware(['auth', 'verified'])->name('admin.atur-waktu.countdown');
});

require __DIR__ . '/auth.php';
