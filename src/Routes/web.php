<?php

use Graweb\Apidoc\Http\Controllers\GrawebApiDocController;
use Illuminate\Support\Facades\Route;

Route::prefix('apidoc')->group(function () {
    Route::get('/', [GrawebApiDocController::class, 'index']);
});
