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
Route::get('/books', [BookController::class, 'index']);         // GET all books
Route::get('/books/{id}', [BookController::class, 'show']);     // GET single book
Route::post('/books', [BookController::class, 'store']);        // ✅ FIXED: Create book
Route::put('/books/{id}', [BookController::class, 'update']);   // Update book
Route::delete('/books/{id}', [BookController::class, 'destroy']); // Delete book
Route::get('/books/search', [BookController::class, 'search']); // Search books
// Route::get('/books/test', [BookController::class, 'test']);       // Test route for books


