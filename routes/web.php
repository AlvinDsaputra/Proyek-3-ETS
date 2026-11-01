<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DistributionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RecapController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return redirect()->route('reports.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/api/schools/{school}/distributions', [DistributionController::class, 'getBySchool'])
        ->name('api.distributions.by-school');

    Route::resource('reports', ReportController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/reports/{report}/review', [ReportController::class, 'review'])->name('reports.review');
    Route::post('/reports/{report}/forward', [ReportController::class, 'forward'])->name('reports.forward');    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/recap', [RecapController::class, 'index'])->name('recap.index');
});

require __DIR__.'/auth.php';
