<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonRequest;
use App\Models\Member;
use     Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $members = Member::all();
        return response()->json([
            'message' => 'Request successful',
            'data' => $members
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $members = Member::create([
        'firstName' => $request->firstName,
        'lastName' => $request->lastName,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'about' => $request->about,
        'address'=>$request->address
    ]);
        
       if($members){
        return response()->json([
            'message' => 'Member create successfully',
            'data'=> $members
        ],201);
       }
       return response()->json([
        'message' => 'create not found'
    ], 500);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $member = Member::create([
        'firstName' => $request->firstName,
        'lastName' => $request->lastName,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'about' => $request->about,
        'address'=>$request->address
    ]);

    if ($member) {
        return response()->json([
            'message' => 'Member created successfully',
            'data' => $member
        ], 201);
    }

    return response()->json([
        'message' => 'Failed to create member'
    ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
          $members = Member::where('id', $id)->first();

    if ($members) {
        return response()->json([
            'message' => 'member found',
            'data' => $members
        ], 200);
    }
     return response()->json([
        'message' => 'member not found'
    ], 500); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,int $id){
      $updated = Member::where('id', $id)
    ->update([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'about' => $request->about
    ]);

if ($updated) {
    $member = Member::find($id); // Fetch the updated member

    return response()->json([
        'message' => 'member info updated successfully',
        'data' => $member
    ], 200);
}

return response()->json([
    'message' => 'Member not found or update failed'
], 500);}


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, int $id)
{
    $updated = Member::where('id', $id)->update([
        'full_name' => $request->full_name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'about' => $request->about,
    ]);

    if ($updated) {
        // Fetch the updated member
        $member = Member::find($id);

        return response()->json([
            'message' => 'Member updated successfully',
            'data' => $member
        ], 200);
    }

    return response()->json([
        'message' => 'Member not found or update failed'
    ], 404);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
         $members = Member::where('id', $id)->delete();

    if ($members) {
        return response()->json([
            'message' => 'Member deleted successfully',
            'deleted' => $members
        ], 200); 
    }

    return response()->json([
        'message' => 'Member not found'
    ], 404);
    }
}
