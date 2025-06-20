<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookModel;



class BookController extends Controller
{
    // public function create()
    // {
    //     // This method is not needed for API controllers
    //     return response()->json(['message' => 'Method not allowed'], 405);
    // }
    // Show all books
    public function index()
    {
        $books = BookModel::all();
        return response()->json($books);
    }

    // Show single book by ID
    public function show($id)
    {
        $book = BookModel::findOrFail($id);
        return response()->json($book);
    }

    // Store a new book
    public function store(Request $request)
    {
$validated = $request->validate([
    'title' => 'required|string',
    'author' => 'required|string',
    'published_date' => 'required|date',
    'ISBN' => 'required|string|unique:books',
    'image' => 'nullable|string',
    'category' => 'required|string',
    'publication_year' => 'required|integer',
    'available_copies' => 'required|integer',
]);


    $book = BookModel::create($validated);
    return response()->json($book, 201);
}


    // Update existing book
    public function update(Request $request, $id)
    {
        $book = BookModel::findOrFail($id);

       $validated = $request->validate([
        'title' => 'sometimes|string',
        'author' => 'sometimes|string',
        'published_date' => 'sometimes|date',
        'ISBN' => 'sometimes|string|unique:books,ISBN,' . $id,
        'image' => 'sometimes|string',
        'category' => 'sometimes|string',
        'publication_year' => 'sometimes|integer',
        'available_copies' => 'sometimes|integer',
     ]);


        $book->update($validated);
        return response()->json($book);
    }

    // Search books by title or author
   public function search(Request $request)
{
    $query = $request->input('query');

    $books = BookModel::where('title', 'LIKE', "%{$query}%")
        ->orWhere('author', 'LIKE', "%{$query}%")
        ->get();

    return response()->json($books);
}


    // Delete book
    public function destroy($id)
    {
        $book = BookModel::findOrFail($id);
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }

    // Test route
    public function test()
    {
        return response()->json(['message' => 'API working!']);
    
    }
}