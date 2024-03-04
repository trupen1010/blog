<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->guard('sanctum')->check()) {
            $categories = Category::get();
            return response()->json(["status" => 200, "error" => 0, "message" => "Success", "categories" => $categories->toArray()], 201);
        }
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (auth()->guard('sanctum')->check()) {
            $categories = Category::find($id);
            return response()->json(["status" => 200, "error" => 0, "message" => "Get Category Successfully", "categories" => $categories->toArray()], 200);
        }
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
    }
}
