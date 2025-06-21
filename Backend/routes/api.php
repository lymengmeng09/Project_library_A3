<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;





// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API working!']);
});


Route::post('/', function () {
    return response()->json(['message' => 'POST request received!']);
});

// Books routes
Route::get('/books', [BookController::class, 'index']);
Route::get('/books/{id}', [BookController::class, 'show']);
Route::post('/books', [BookController::class, 'store']);
Route::put('/books/{id}', [BookController::class, 'update']);
Route::delete('/books/{id}', [BookController::class, 'destroy']);
Route::get('/search', [BookController::class, 'search']);



