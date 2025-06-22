<?php

use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\MemberController;
use App\http\Controllers\AuthorController;
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

//route member
Route::prefix('/members')->group(function(){
    Route::get('/',[MemberController::class,"index"])->name("/allmembers");
    Route::put("/edit/{id}",[MemberController::class, 'edit']);
    Route::post("/create",[MemberController::class, 'create']);
    Route::delete("/delete/{id}", [MemberController::class, 'destroy']);
    Route::post('/store', [MemberController::class, 'store']);
    Route::get("/show/{id}", [MemberController::class, 'show']);
    Route::put('/update/{id}', [MemberController::class, 'update']);
});

//route author
Route::prefix('/authors')->group(function(){
    Route::get('/',[AuthorController::class,"index"])->name("/allauthors");
    Route::put("/edit/{id}",[AuthorController::class, 'edit']);
    Route::post("/create",[AuthorController::class, 'create']);
    Route::delete("/delete/{id}", [AuthorController::class, 'destroy']);
    Route::post('/store', [AuthorController::class, 'store']);
    Route::get("/show/{id}", [AuthorController::class, 'show']);
    Route::put('/update/{id}', [AuthorController::class, 'update']);
});




// Book Routes
Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('books.index'); // GET all books
    Route::post('/create', [BookController::class, 'create']);               // GET
    Route::post('/store', [BookController::class, 'store']);               // POST new book
    Route::get('/show/{id}', [BookController::class, 'show']);             // GET one book
    Route::put('/update/{id}', [BookController::class, 'update']);           // PUT update book
    Route::delete('/destroy/{id}', [BookController::class, 'destroy']);       // DELETE book
});

// Book search route
Route::get('/search', [BookController::class, 'search']);







Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});