<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (auth()->guard('sanctum')->check()) {
                $tags = Tag::get();
                if (!$tags) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Tags not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Tags Successfully", "tags" => $tags->toArray()], 201);
            }
            return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
        } catch (\Throwable $th) {
            Log::error("500 => TagController => Index => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            if (auth()->guard('sanctum')->check()) {
                $tag = Tag::find($id);
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Tag Successfully", "tag" => $tag->toArray()], 200);
            }
            return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
        } catch (\Throwable $th) {
            Log::error("500 => TagController => Show => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update()
    {
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
    }
}
