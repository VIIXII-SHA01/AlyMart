<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('cover-login/mainpage');
});

Route::get('/login', function () {
    return view('cover-login/login');
});
Route::get('/dashboard-admin', function () {
    return view('for_admin/dashboard');
});