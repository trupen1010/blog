<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $tags = Tag::get();
                if (!$tags) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Tags not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Tags Successfully", "tags" => $tags->toArray()], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => TagController => Index => " . $th);
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
                $tags = Tag::orderBy('id', 'DESC')->get(['id', 'name', 'created_at']);
                if (!$tags) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Tags not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get tags Successfully", "tags" => TagResource::collection($tags)], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => TagController => Datatable => " . $th);
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
    public function store(Request $request)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'slug' => 'required|string|unique:tags',
                ]);

                if ($validator->fails()) {
                    return response()->json(["status" => 422, "error" => 1, "message" => "Validation failed", "errors" => $validator->errors()->toArray()], 200);
                }

                $tag = Tag::create([
                    'name' => $request->name,
                    'slug' => $request->slug,
                ]);
                return response()->json(["status" => 201, "error" => 0, "message" => "Tag created successfully", "data" => $tag->toArray(),], 201);
            }
        } catch (\Throwable $th) {
            Log::error("500 => TagController => Store => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $tag = Tag::find($id);
                if (!$tag) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Tag not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Tag Successfully", "tag" => $tag->toArray()], 200);
            }
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
    public function update(Request $request, $id)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $tag = Tag::find($id);
                if (!$tag) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Tag not found"], 404);
                }

                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'slug' => 'required|string|unique:tags,slug,' . $tag->id,
                ]);

                if ($validator->fails()) {
                    return response()->json(["status" => 422, "error" => 1, "message" => "Validation failed", "errors" => $validator->errors()->toArray()], 200);
                }

                $tag->update([
                    'name' => $request->name,
                    'slug' => $request->slug,
                ]);

                return response()->json(["status" => 200, "error" => 0, "message" => "Tag updated successfully", "data" => $tag->toArray()], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => TagController => Update => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $tag = Tag::find($id);
                if (!$tag) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Tag not found"], 404);
                }
                $tag->delete();
                return response()->json(["status" => 200, "error" => 0, "message" => "Tag deleted successfully"], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => TagController => Delete => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }
}
