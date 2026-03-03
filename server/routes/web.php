<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LicenceController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::get('/login', function () {
    return view('login');
})->name('showLogin');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/licences', [LicenceController::class, 'all'])->name('licences.all')->middleware('auth');
Route::post('/licences', [LicenceController::class, 'create'])->name('licences.create')->middleware('auth');
Route::post('/revoke/{id}', [LicenceController::class, 'revoke'])->name('licences.revoke')->middleware('auth');;
