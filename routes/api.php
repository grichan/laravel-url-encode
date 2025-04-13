<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UrlEncoderController;

Route::get('/', [UrlEncoderController::class, 'index']);

Route::get('/encode', [UrlEncoderController::class, 'encode']);

Route::get('/decode', [UrlEncoderController::class, 'decode']);
