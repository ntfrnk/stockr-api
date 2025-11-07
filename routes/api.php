<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('jwt')->group(function(){

    Route::get('/test', fn() => App\Classes\MakeResponse::success()->message("You're logged in!")->get());
    
});
