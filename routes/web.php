<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\FeatureRequestController;
use App\Http\Controllers\ClientCoordinationController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified', 'prevent-back-history'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware(['role:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserManagementController::class);
        Route::resource('modules', \App\Http\Controllers\Admin\ModuleManagementController::class);
        Route::get('feature-requests', [FeatureRequestController::class, 'index'])->name('feature-requests.index');

        Route::post('feature-requests/{featureRequest}/handle', [FeatureRequestController::class, 'handle'])->name('feature-requests.handle');
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::post('users/{user}/features/{feature}/toggle', [UserManagementController::class, 'toggleFeature'])->name('users.features.toggle');
    });

    // Dynamic Module Routes
    require __DIR__ . '/modules.php';

    // Feature Requests for Users
    Route::post('feature-requests', [FeatureRequestController::class, 'store'])->name('feature-requests.store');

    });

require __DIR__.'/auth.php';
