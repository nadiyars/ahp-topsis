<?php

use App\Http\Controllers\AhpController;
use Illuminate\Support\Facades\Route;

Route::prefix("admin")->middleware("auth")->as("admin.")->group(function () {
    Route::prefix("ahp")->as("ahp.")->group(function () {
        Route::get("/", [AhpController::class, 'index'])->name("index");
    });
});
