<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DrugController;
use App\Http\Controllers\Admin\PaymentTypeController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\BillingController;


Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/transaction', [DashboardController::class, 'transaction'])->name('transaction.index');

    Route::resource('patients', PatientController::class);

    Route::get('/obat', [DrugController::class, 'index'])->name('drug.index');

    Route::resource('polis', PoliController::class);
    Route::resource('dokters', DokterController::class);

    Route::get('/pendaftaran', [RegistrationController::class, 'index'])->name('registration.index');
    Route::post('/pendaftaran', [RegistrationController::class, 'store'])->name('registration.store');
    Route::get('/pendaftaran/create', [RegistrationController::class, 'create'])->name('registration.create');
    Route::delete('/pendaftaran/{register}', [RegistrationController::class, 'destroy'])->name('registration.destroy');

    Route::resource('payment-type', PaymentTypeController::class)->names('payment.type');

    Route::get('/billing', [BillingController::class, 'showSearchForm'])->name('billing.search');
    Route::post('/billing/show', [BillingController::class, 'showBillingDetails'])->name('billing.show');
    Route::post('/billing/{transaction}/pay', [BillingController::class, 'processPayment'])->name('billing.pay');
});
