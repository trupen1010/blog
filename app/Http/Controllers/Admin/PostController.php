<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (auth()->guard('sanctum')->check()) {
                $posts = Post::get();
                if ($posts) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Post not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Post Successfully", "posts" => $posts->toArray()], 200);
            }
            return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
        } catch (\Throwable $th) {
            Log::error("500 => PostController => Index => " . $th);
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
                    'author_id' => 'required',
                    'title' => 'required|string',
                    'sub_title' => 'required|string',
                    'publish_date' => 'required',
                    'description' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(["status" => 422, "error" => 1, "message" => "Validation failed", "errors" => $validator->errors()->toArray()], 422);
                }

                $image = uploadFile(public_path() . '/images/Post/', $request->file('image'), false, true);

                $post = Post::create([
                    'author_id' => $request->author_id,
                    'title' => $request->title,
                    'sub_title' => $request->sub_title,
                    'publish_date' => $request->publish_date,
                    'image' => $image,
                    'description' => $request->description,
                ]);
                return response()->json(["status" => 201, "error" => 0, "message" => "Post created successfully", "data" => $post->toArray()], 201);
            }
        } catch (\Throwable $th) {
            Log::error("500 => PostController => Store => " . $th);
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
                $post = Post::find($id);
                if (!$post) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Post not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Post Successfully", "Post" => $post->toArray()], 200);
            }
            return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
        } catch (\Throwable $th) {
            Log::error("500 => PostController => Show => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
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
                $post = Post::find($id);
                if (!$post) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Post not found"], 404);
                }

                $validator = Validator::make($request->all(), [
                    'author_id' => 'required',
                    'title' => 'required|string',
                    'sub_title' => 'required|string',
                    'publish_date' => 'required',
                    'description' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(["status" => 422, "error" => 1, "message" => "Validation failed", "errors" => $validator->errors()->toArray()], 422);
                }

                $image = updateFile(public_path() . '/images/Post/', $post->image, $request->file('image'), false, true);

                $post->update([
                    'author_id' => $request->author_id,
                    'title' => $request->title,
                    'sub_title' => $request->sub_title,
                    'publish_date' => $request->publish_date,
                    'image' => $image,
                    'description' => $request->description,
                ]);

                return response()->json(["status" => 200, "error" => 0, "message" => "Post updated successfully", "data" => $post->toArray()], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => PostController => Update => " . $th);
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
                $post = Post::find($id);
                if (!$post) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Post not found"], 404);
                }
                $post->delete();
                return response()->json(["status" => 200, "error" => 0, "message" => "Post deleted successfully"], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => PostController => Delete => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }
}
