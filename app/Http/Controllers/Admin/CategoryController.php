<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            return response()->json(["status" => 200, "error" => 0, "message" => "Get Categories Successfully", "categories" => $categories->toArray()], 200);
        }
        return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->guard('sanctum')->check()) {
            return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
        } else {
            $request->validate([
                'name' => 'required|string|max:255',
                'slug' => 'required|string|unique:categories',
            ]);

            $category = Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
            ]);

            return response()->json([
                "status" => 201, // Created status code
                "error" => 0,
                "message" => "Category created successfully",
                "data" => $category->toArray(),
            ], 201);
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!auth()->guard('sanctum')->check()) {
            return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
        } else {
            $category = Category::find($id);
            if (!$category) {
                return response()->json(["status" => 404, "error" => 1, "message" => "Category not found"], 404);
            }

            $request->validate([
                'name' => 'nullable|string|max:255',
                'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
            ]);

            $category->update([
                'name' => $request->name,
                'slug' => $request->slug,
            ]);

            return response()->json(["status" => 200, "error" => 0, "message" => "Category updated successfully", "data" => $category->toArray()], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!auth()->guard('sanctum')->check()) {
            return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
        } else {
            $category = Category::find($id);
            if (!$category) {
                return response()->json(["status" => 404, "error" => 1, "message" => "Category not found"], 404);
            }
            $category->delete();
            return response()->json(["status" => 200, "error" => 0, "message" => "Category deleted successfully"], 200);
        }
    }
}
