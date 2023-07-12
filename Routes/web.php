<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('authentication.')->group(function() {
    Route::post('/login', [\Modules\Authentication\Http\Controllers\AuthenticationController::class, 'login'])->name('login');
});
