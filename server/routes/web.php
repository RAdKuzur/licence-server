<?php

use App\Http\Controllers\LicenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/licences', [LicenceController::class, 'all'])->name('licences.all');
Route::post('/licences', [LicenceController::class, 'create'])->name('licences.create');
Route::post('/revoke/{id}', [LicenceController::class, 'revoke'])->name('licences.revoke');
