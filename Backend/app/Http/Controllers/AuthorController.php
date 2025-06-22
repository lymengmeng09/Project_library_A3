<?php

namespace App\Http\Controllers;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::all();
        return response()->json([
            'message' => 'Request successful',
            'data' => $authors
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $author = Author::create([
        'name' =>  $request->name,
        'nationality' =>  $request->nationality,
        'written_book' => $request->written_book,
    ]);

    return response()->json([
        'message' => 'Author created successfully using create()',
        'data' => $author
    ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $author = new Author();
        $author->name = $request->name;
        $author->nationality = $request->nationality;
        $author->written_book = $request->written_book;
        $author->save();

    return response()->json([
        'message' => 'Author stored successfully',
        'data' => $author
    ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        return response()->json([
            'message' => 'Author retrieved successfully',
            'data' => $author
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
        public function edit(string $id)
        {
            $author = Author::find($id);

            if (!$author) {
                return response()->json(['message' => 'Author not found'], 404);
            }

            return response()->json([
                'message' => 'Display form to edit author',
                'data' => $author
            ]);
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $author = Author::find($id);

    if (!$author) {
        return response()->json(['message' => 'Author not found'], 404);
    }

    $author->name = $request->name;
    $author->nationality = $request->nationality;
    $author->written_book = $request->written_book;
    $author->save();

    return response()->json([
        'message' => 'Author updated successfully',
        'data' => $author
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
                $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $author->delete();

        return response()->json([
            'message' => 'Author deleted successfully'
        ]);

    }
}
