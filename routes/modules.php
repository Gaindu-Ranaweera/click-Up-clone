<?php

use Illuminate\Support\Facades\Route;


// |--------------------------------------------------------------------------
// | Dynamic Module Routes
// |--------------------------------------------------------------------------
// |
// | These routes are automatically generated when a new module is created via 
// | the Module Management dashboard.
// |


// Core Modules
Route::middleware(['feature:module_client_coordination'])->prefix('client-coordination')->name('client-coordination.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ClientCoordinationController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\ClientCoordinationController::class, 'store'])->name('store');
    Route::patch('/{client}', [\App\Http\Controllers\ClientCoordinationController::class, 'update'])->name('update');
    Route::post('/{client}/followup', [\App\Http\Controllers\ClientCoordinationController::class, 'storeFollowup'])->name('followup');
    Route::delete('/{client}', [\App\Http\Controllers\ClientCoordinationController::class, 'destroy'])->name('destroy');
});

Route::middleware(['feature:module_finance'])->prefix('finance')->name('finance.')->group(function () {
    Route::get('/', [\App\Http\Controllers\InvoiceController::class, 'index'])->name('index');
    Route::get('/invoices/create', [\App\Http\Controllers\InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoices', [\App\Http\Controllers\InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/edit', [\App\Http\Controllers\InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::patch('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'destroy'])->name('destroy');
    Route::get('/clients/{client}', [\App\Http\Controllers\InvoiceController::class, 'getClientDetails'])->name('clients.details');
});

// Utility Modules
Route::middleware(['feature:module_notifications'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', fn() => view('modules.notifications.index'))->name('index');
});

Route::middleware(['feature:module_audit_logs'])->prefix('audit-logs')->name('audit-logs.')->group(function () {
    Route::get('/', fn() => view('modules.audit-logs.index'))->name('index');
});

// Dynamic Modules (Auto-generated)
Route::middleware(['feature:module_booking'])->prefix('booking')->name('booking.')->group(function () {
    Route::get('/', fn() => view('modules.booking.index'))->name('index');
});
