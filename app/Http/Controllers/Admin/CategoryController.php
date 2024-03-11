<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $categories = Category::get();
                if (!$categories) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Categories not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Categories Successfully", "categories" => $categories->toArray()], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => CategoryController => Index => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }



    /**
     * datatable
     *
     * @return void
     */
    public function datatable()
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $categories = Category::orderBy('id', 'DESC')->get(['id', 'name', 'created_at']);
                if (!$categories) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Categories not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Categories Successfully", "categories" => CategoryResource::collection($categories)], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => CategoryController => Datatable => " . $th);
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
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'slug' => 'required|string|unique:categories',
                ]);

                if ($validator->fails()) {
                    return response()->json(["status" => 422, "error" => 1, "message" => "Validation failed", "errors" => $validator->errors()->toArray()], 200);
                }

                $category = Category::create([
                    'name' => $request->name,
                    'slug' => $request->slug,
                ]);

                return response()->json(["status" => 201, "error" => 0, "message" => "Category created successfully", "data" => $category->toArray(),], 201);
            }
        } catch (\Throwable $th) {
            Log::error("500 => CategoryController => Store => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return void
     */
    public function show($id)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $category = Category::find($id);
                if (!$category) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Category not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Category Successfully", "categories" => $category->toArray()], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => CategoryController => Show => " . $th);
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
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $category = Category::find($id);
                if (!$category) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Category not found"], 404);
                }

                $validator = Validator::make($request->all(), [
                    'name' => 'nullable|string|max:255',
                    'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
                ]);

                if ($validator->fails()) {
                    return response()->json(["status" => 422, "error" => 1, "message" => "Validation failed", "errors" => $validator->errors()->toArray()], 200);
                }

                $category->update([
                    'name' => $request->name,
                    'slug' => $request->slug,
                ]);
                return response()->json(["status" => 200, "error" => 0, "message" => "Category updated successfully", "data" => $category->toArray()], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => CategoryController => Update => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $category = Category::find($id);
                if (!$category) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Category not found"], 404);
                }
                $category->delete();
                return response()->json(["status" => 200, "error" => 0, "message" => "Category deleted successfully"], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => CategoryController => Delete => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }
}
