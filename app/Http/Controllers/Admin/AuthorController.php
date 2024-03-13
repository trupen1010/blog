<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (auth()->guard('sanctum')->check()) {
                $author = Author::get();
                if (!$author) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Author not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Author Successfully", "author" => $author->toArray()], 200);
            }
            return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
        } catch (\Throwable $th) {
            Log::error("500 => AuthorController => Index => " . $th);
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
                $author = Author::orderBy('id', 'DESC')->get(['id', 'name', 'image']);
                if (!$author) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Authors not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get author Successfully", "authors" => AuthorResource::collection($author)], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => AuthorController => Datatable => " . $th);
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
                    'name' => 'required',
                    'image' => 'required|image'
                ]);
                if ($validator->fails()) {
                    return response()->json(["status" => 422, "error" => 1, "message" => "Validation failed", "errors" => $validator->errors()->toArray()], 200);
                }
                $image = uploadFile(public_path() . '/images/Author/', $request->file('image'), false, true);
                $author = Author::create([
                    'name' => $request->name,
                    'image' => $image
                ]);
                return response()->json(["status" => 201, "error" => 0, "message" => "Author created successfully", "data" => $author->toArray()], 201);
            }
        } catch (\Throwable $th) {
            Log::error("500 => AuthorController => Store => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            if (auth()->guard('sanctum')->check()) {
                $author = Author::find($id);
                if (!$author) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Author not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Author Successfully", "author" => $author->toArray()], 200);
            }
            return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
        } catch (\Throwable $th) {
            Log::error("500 => AuthorController => Show => " . $th);
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
                $author = Author::find($id);
                if (!$author) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Author not found"], 404);
                }

                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'image' => 'nullable|image',
                ]);

                if ($validator->fails()) {
                    return response()->json(["status" => 422, "error" => 1, "message" => "Validation failed", "errors" => $validator->errors()->toArray()], 200);
                }

                /* if (isset($request->image) && !empty($request->image)) {
                    $image = uploadFile(public_path() . '/images/Author/', $request->file('image'), false, true);
                    deleteImage(public_path() . '/images/Author/' . $author->image);
                    deleteImage(public_path() . '/images/Author/thumbnails/' . $author->image);
                } else {
                    $image = $author->image;
                } */
                $image = updateFile(public_path() . '/images/Author/', $author->image, $request->file('image'), false, true, false);
                $author->update([
                    'name' => $request->name,
                    'image' => $image,
                ]);

                return response()->json(["status" => 200, "error" => 0, "message" => "Author updated successfully", "data" => $author->toArray()], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => AuthorController => Update => " . $th);
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
                $author = Author::find($id);
                if (!$author) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Author not found"], 404);
                }
                $author->delete();
                return response()->json(["status" => 200, "error" => 0, "message" => "Author deleted successfully"], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => AuthorController => Delete => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }
}
