<?php

use App\Events\MessageSent;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

