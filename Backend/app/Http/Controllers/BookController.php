<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookModel;

class BookController extends Controller
{
    // List all books
    public function index()
    {
        $books = BookModel::all();

        return response()->json([
            'message' => 'Books retrieved successfully',
            'data' => $books
        ], 200);
    }

    // Create book form (not needed for API but you can keep it if you want)
    public function create(Request $request)
    {
        $book = BookModel::create([
            'title' =>  $request->title,
            'author' => $request->author,
            'ISBN' => $request->ISBN,
            'image' => $request->image,
            'category' => $request->category,
            'publication_year' => $request->publication_year,
            'available_copies' => $request->available_copies,
        ]);

        if ($book) {
            return response()->json([
                'message' => 'Book created successfully',
                'data' => $book
            ], 201);
        }

        return response()->json([
            'message' => 'Failed to create book'
        ], 500);
    }

    // Store new book (with validation)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'ISBN' => 'required|string|unique:books',
            'image' => 'nullable|string',
            'category' => 'required|string',
            'publication_year' => 'required|integer',
            'available_copies' => 'required|integer',
        ]);

        $book = BookModel::create($validated);

        if ($book) {
            return response()->json([
                'message' => 'Book created successfully',
                'data' => $book
            ], 201);
        }

        return response()->json([
            'message' => 'Failed to create book'
        ], 500);
    }

    // Show a single book
    public function show($id)
    {
        $book = BookModel::find($id);

        if ($book) {
            return response()->json([
                'message' => 'Book found',
                'data' => $book
            ], 200);
        }

        return response()->json([
            'message' => 'Book not found'
        ], 404);
    }

    // Edit book info (optional, usually same as update)
    public function edit(Request $request, $id)
    {
        $book = BookModel::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        $updated = $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'ISBN' => $request->ISBN,
            'image' => $request->image,
            'category' => $request->category,
            'publication_year' => $request->publication_year,
            'available_copies' => $request->available_copies,
        ]);

        if ($updated) {
            return response()->json([
                'message' => 'Book updated successfully',
                'data' => $book
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to update book'
        ], 500);
    }

    // Update book (with validation)
    public function update(Request $request, $id)
    {
        $book = BookModel::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string',
            'author' => 'sometimes|string',
            'ISBN' => 'sometimes|string|unique:books,ISBN,' . $id,
            'image' => 'sometimes|nullable|string',
            'category' => 'sometimes|string',
            'publication_year' => 'sometimes|integer',
            'available_copies' => 'sometimes|integer',
        ]);

        $updated = $book->update($validated);

        if ($updated) {
            return response()->json([
                'message' => 'Book updated successfully',
                'data' => $book
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to update book'
        ], 500);
    }

    // Delete a book
    public function destroy($id)
    {
        $book = BookModel::find($id);

        if (!$book) {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        $deleted = $book->delete();

        if ($deleted) {
            return response()->json([
                'message' => 'Book deleted successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to delete book'
        ], 500);
    }

    // Search books by title or author
    public function search(Request $request)
    {
        $query = $request->input('query');

        $books = BookModel::where('title', 'LIKE', "%{$query}%")
            ->orWhere('author', 'LIKE', "%{$query}%")
            ->get();

        return response()->json([
            'message' => 'Search results',
            'data' => $books
        ], 200);
    }
}