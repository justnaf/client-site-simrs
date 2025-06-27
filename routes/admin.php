<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PaymentTypeController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\PatientController;


Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('patients', PatientController::class);
    Route::get('/pendaftaran', [RegistrationController::class, 'index'])->name('registration.index');
    Route::post('/pendaftaran', [RegistrationController::class, 'store'])->name('registration.store');
    Route::get('/pendaftaran/create', [RegistrationController::class, 'create'])->name('registration.create');
    Route::delete('/pendaftaran/{register}', [RegistrationController::class, 'destroy'])->name('registration.destroy');
    Route::get('/payment-type', [PaymentTypeController::class, 'index'])->name('payment.type.index');
    Route::post('/payment-type', [PaymentTypeController::class, 'store'])->name('payment.type.store');
    Route::get('/payment-type/create', [PaymentTypeController::class, 'create'])->name('payment.type.create');
    Route::delete('/payment-type/{payment}/destroy', [PaymentTypeController::class, 'destroy'])->name('payment.type.destroy');
});
