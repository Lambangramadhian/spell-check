<?php

// use ;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/spell_check', function () {return view('spell_check');});

Route::get('test', function () {return view('test');});