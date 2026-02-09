<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/samuel', function () {
    return view('mivista', ['nombre' => 'Samuel']);
});