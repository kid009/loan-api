<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/store', function () {
    Redis::set('name', 'John Doe');
});

Route::get('/retrieve', function () {
    return Redis::get('name');
});

// Test Mailhog
Route::get('/send-email', function () {
    Mail::to('samit@itgeniussite.dev')->send(new TestMail);
});
