<?php

use App\Http\Controllers\User\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth', 'role:pasien'])->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
});


require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';
