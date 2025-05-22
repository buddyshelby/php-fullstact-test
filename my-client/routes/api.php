<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyClientController;

Route::apiResource('my-clients', MyClientController::class);
Route::post('/my-clients/{id}/upload-logo', [MyClientController::class, 'uploadLogo']);