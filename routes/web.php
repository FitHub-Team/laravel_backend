<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/reset-password', function () {
    return response()->json([
        'message' => 'Reset password page',
        'note' => 'Use token and email from URL'
    ]);
});