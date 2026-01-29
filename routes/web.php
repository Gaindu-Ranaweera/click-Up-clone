<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\FeatureRequestController;
use App\Http\Controllers\ClientCoordinationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes
    Route::middleware(['role:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserManagementController::class);
        Route::get('feature-requests', [FeatureRequestController::class, 'index'])->name('feature-requests.index');
        Route::post('feature-requests/{featureRequest}/handle', [FeatureRequestController::class, 'handle'])->name('feature-requests.handle');
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::post('users/{user}/features/{feature}/toggle', [UserManagementController::class, 'toggleFeature'])->name('users.features.toggle');
    });

    // Feature Requests for Users
    Route::post('feature-requests', [FeatureRequestController::class, 'store'])->name('feature-requests.store');

    // Module Routes
    Route::middleware(['feature:module_client_coordination'])->prefix('client-coordination')->name('client-coordination.')->group(function () {
        Route::get('/', [ClientCoordinationController::class, 'index'])->name('index');
        Route::post('/', [ClientCoordinationController::class, 'store'])->name('store');
        Route::patch('/{client}', [ClientCoordinationController::class, 'update'])->name('update');
        Route::post('/{client}/followup', [ClientCoordinationController::class, 'storeFollowup'])->name('followup');
        Route::delete('/{client}', [ClientCoordinationController::class, 'destroy'])->name('destroy');
    });

    Route::middleware(['feature:module_projects'])->prefix('projects')->name('projects.')->group(function () {
        Route::get('/', fn() => view('modules.projects.index'))->name('index');
    });

    Route::middleware(['feature:module_clients'])->prefix('clients')->name('clients.')->group(function () {
        Route::get('/', fn() => view('modules.clients.index'))->name('index');
    });

    Route::middleware(['feature:module_hr'])->prefix('hr')->name('hr.')->group(function () {
        Route::get('/', fn() => view('modules.hr.index'))->name('index');
    });

    Route::middleware(['feature:module_payroll'])->prefix('payroll')->name('payroll.')->group(function () {
        Route::get('/', fn() => view('modules.payroll.index'))->name('index');
    });

    Route::middleware(['feature:module_finance'])->prefix('finance')->name('finance.')->group(function () {
        Route::get('/', fn() => view('modules.finance.index'))->name('index');
    });

    Route::middleware(['feature:module_notifications'])->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', fn() => view('modules.notifications.index'))->name('index');
    });

    Route::middleware(['feature:module_audit_logs'])->prefix('audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/', fn() => view('modules.audit-logs.index'))->name('index');
    });
});

require __DIR__.'/auth.php';
