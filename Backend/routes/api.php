<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\BookController;





// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API working!']);
=======
use App\http\Controllers\MemberController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/members')->group(function(){
    Route::get('/',[MemberController::class,"index"])->name("/allmembers");
    Route::put("/edit/{id}",[MemberController::class, 'edit']);
    Route::post("/create",[MemberController::class, 'create']);
    Route::delete("/delete/{id}", [MemberController::class, 'destroy']);
    Route::post('/store', [MemberController::class, 'store']);
    Route::get("/show/{id}", [MemberController::class, 'show']);
    Route::put('/update/{id}', [MemberController::class, 'update']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
>>>>>>> 891ac87b162fb8f949a6d3bd752315337a2d90eb
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



