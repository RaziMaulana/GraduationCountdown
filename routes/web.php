<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\admin\OverviewController;
use App\Http\Controllers\admin\TimeSettingController;
use App\Http\Controllers\Graduation\ResultController;
use App\Http\Controllers\admin\AdminAccountController;
use App\Http\Controllers\Graduation\GraduationTimeController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('kelulusan')->group(function () {
    Route::get('/dashboard', [GraduationTimeController::class, 'GraduationTimeIndex'])->middleware(['auth', 'verified'])->name('kelulusan.index');
    Route::get('/countdown', [GraduationTimeController::class, 'getCountdown'])->middleware(['auth', 'verified'])->name('kelulusan.countdown');
    Route::get('/hasil/{status?}', [ResultController::class, 'ResultIndex'])->middleware(['auth', 'verified'])->name('kelulusan.hasil');
});

Route::prefix('admin')->group(function () {
    Route::get('/accounts', [AdminAccountController::class, 'AdminAccountIndex'])->middleware(['auth', 'verified'])->name('admin.accounts.index');
    Route::put('/accounts/{id}', [AdminAccountController::class, 'update'])->middleware(['auth', 'verified'])->name('admin.accounts.update');
    Route::post('/admin/accounts/{id}/upload-photo', [AdminAccountController::class, 'uploadPhoto'])->middleware(['auth', 'verified'])->name('admin.accounts.upload-photo');

    Route::get('/manajemen-data', [OverviewController::class, 'OverviewIndex'])->middleware(['auth', 'verified'])->name('admin.manajemen-data');
    Route::get('/manajemen-data/download-template', [OverviewController::class, 'downloadExcelTemplate'])->middleware(['auth', 'verified'])->name('admin.manajemen-data.excel-template');
    Route::get('/admin/manajemen-data/count', [OverviewController::class, 'getStudentCount'])->middleware(['auth', 'verified'])->name('admin.manajemen-data.count');
    Route::post('/manajemen-data/store', [OverviewController::class, 'OverviewStore'])->middleware(['auth', 'verified'])->name('admin.manajemen-data.store');
    Route::post('/admin/manajemen-data/import', [OverviewController::class, 'import'])->middleware(['auth', 'verified'])->name('admin.manajemen-data.import');
    Route::delete('/manajemen-data/destroy-all', [OverviewController::class, 'destroyAll'])->middleware(['auth', 'verified'])->name('admin.manajemen-data.destroy-all');
    Route::get('/manajemen-data/{id}', [OverviewController::class, 'OverviewShow'])->middleware(['auth', 'verified'])->name('admin.manajemen-data.show');
    Route::get('/manajemen-data/{id}/edit', [OverviewController::class, 'OverviewEdit'])->middleware(['auth', 'verified'])->name('admin.manajemen-data.edit');
    Route::put('/manajemen-data/{id}', [OverviewController::class, 'OverviewUpdate'])->middleware(['auth', 'verified'])->name('admin.manajemen-data.update');
    Route::delete('/manajemen-data/{id}', [OverviewController::class, 'OverviewDestroy'])->middleware(['auth', 'verified'])->name('admin.manajemen-data.destroy');

    Route::get('/atur-waktu', [TimeSettingController::class, 'TimeSettingIndex'])->middleware(['auth', 'verified'])->name('admin.atur-waktu');
    Route::get('/atur-waktu/countdown', [TimeSettingController::class, 'getCountdown'])->middleware(['auth', 'verified'])->name('admin.atur-waktu.countdown');
    Route::post('/atur-waktu/countdown', [TimeSettingController::class, 'setCountdown'])->middleware(['auth', 'verified'])->name('admin.atur-waktu.countdown');
});

require __DIR__ . '/auth.php';
