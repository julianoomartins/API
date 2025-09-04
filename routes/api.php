<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/user', function (Request $request) {
    return response()->json([
        'status' => true,
        'users' => "Lista de usuários!"
    ]);
});

Route::get('/class', function (Request $request) {
    return response()->json([
        'status' => true,
        'users' => "Lista de class!"
    ]);
});


