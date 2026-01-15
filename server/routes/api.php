<?php

use App\Http\Controllers\LicenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function (Request $request) {
    return 'test';
});
Route::post('/licence', [LicenceController::class, 'licence'])->name('licence');
