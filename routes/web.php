<?php

use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Log::info('test');
});

