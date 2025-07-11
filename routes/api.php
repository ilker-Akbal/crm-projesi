<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Şimdilik basit bir “health check” bırakalım.
| İleride gerçek API uç-noktalarınızı burada tanımlarsınız.
*/
Route::middleware('api')->get('/health', function () {
    return response()->json(['status' => 'ok']);
});
