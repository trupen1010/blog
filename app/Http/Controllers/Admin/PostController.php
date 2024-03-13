<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Author;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;
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
                $data['categories'] = Category::get();
                $data['tags'] = Tag::get();
                $data['authors'] = Author::get();
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Data Successfully", "data" => $data], 200);
            }
            return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
        } catch (\Throwable $th) {
            Log::error("500 => PostController => Index => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    public function datatable()
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $post = Post::orderBy('id', 'DESC')->get();
                if (!$post) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Posts not found"], 404);
                }
                return response()->json(["status" => 200, "error" => 0, "message" => "Get posts Successfully", "posts" => PostResource::collection($post)], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => PostController => Datatable => " . $th);
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
    /* public function store(Request $request, Post $post)
    {
        try {
            if (!auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            } else {
                $validator = Validator::make($request->all(), [
                    'category_ids' => 'required',
                    'tag_ids' => 'required',
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
                $post->author_id = $request->author_id;
                $post->title = $request->title;
                $post->sub_title = $request->sub_title;
                $post->publish_date = Carbon::createFromFormat('D, d M Y H:i:s e', 'Thu, 28 Mar 2024 18:30:00 GMT')->toDateTimeString();
                $post->image = $image;
                $post->description = $request->description;

                $post->save();

                foreach (explode(',', $request->category_ids) as $categoryId) {
                    $post->post_category()->create([
                        "post_id" => $post->id,
                        "category_id" => $categoryId
                    ]);
                }

                foreach (explode(',', $request->tag_ids) as $tagId) {
                    $post->post_Tag()->create([
                        "post_id" => $post->id,
                        "tag_id" => $tagId
                    ]);
                }

                return response()->json(["status" => 201, "error" => 0, "message" => "Post created successfully", "data" => $post->toArray()], 201);
            }
        } catch (\Throwable $th) {
            Log::error("500 => PostController => Store => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    } */

    public function store(Request $request, Post $post)
    {
        try {
            $validator = Validator::make($request->all(), [
                'category_ids' => 'required',
                'tag_ids' => 'required',
                'author_id' => 'required',
                'title' => 'required|string',
                'sub_title' => 'required|string',
                'publish_date' => 'required',
                'description' => 'required',
                'sequence' => 'nullable|numeric',
                'is_featured' => 'nullable|boolean',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(["status" => 422, "error" => 1, "message" => "Validation failed", "errors" => $validator->errors()->toArray()], 422);
            }

            $image = uploadFile(public_path() . '/images/Post/', $request->file('image'), false, true);
            if (!$image) {
                return response()->json(["status" => 500, "error" => 1, "message" => "Failed to upload image"], 500);
            }

            $post->author_id = $request->author_id;
            $post->title = $request->title;
            $post->sub_title = $request->sub_title;
            $post->publish_date = Carbon::createFromFormat('D, d M Y H:i:s e', $request->publish_date)->toDateTimeString();
            $post->image = $image;
            $post->description = $request->description;
            $post->sequence = $request->sequence;
            $post->is_featured = $request->is_featured;

            $post->save();

            foreach (explode(',', $request->category_ids) as $categoryId) {
                $post->post_categories()->create([
                    "category_id" => $categoryId
                ]);
            }

            foreach (explode(',', $request->tag_ids) as $tagId) {
                $post->post_tags()->create([
                    "tag_id" => $tagId
                ]);
            }

            return response()->json(["status" => 201, "error" => 0, "message" => "Post created successfully", "data" => $post->toArray()], 201);
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
                $data['post'] = Post::with(['post_categories', 'post_tags', 'author'])->find($id);
                if (empty($data['post'])) {
                    return response()->json(["status" => 404, "error" => 1, "message" => "Post not found"], 404);
                }
                $data['category_ids'] = $data['post']->post_categories->pluck('category_id');
                $data['tag_ids'] = $data['post']->post_tags->pluck('tag_id');
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Post Successfully", "post" => $data], 200);
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
                    'category_ids' => 'required',
                    'tag_ids' => 'required',
                    'author_id' => 'required',
                    'title' => 'required|string',
                    'sub_title' => 'required|string',
                    'publish_date' => 'required',
                    'description' => 'required',
                    'sequence' => 'nullable|numeric',
                    'is_featured' => 'nullable|boolean',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                if ($validator->fails()) {
                    return response()->json(["status" => 422, "error" => 1, "message" => "Validation failed", "errors" => $validator->errors()->toArray()], 422);
                }

                $image = updateFile(public_path() . '/images/Post/', $post->image, $request->file('image'), false, true, false);

                $post->author_id = $request->author_id;
                $post->title = $request->title;
                $post->sub_title = $request->sub_title;
                $post->publish_date = Carbon::createFromFormat('D, d M Y H:i:s e', $request->publish_date)->toDateTimeString();
                $post->image = $image;
                $post->description = $request->description;
                $post->sequence = $request->sequence;
                $post->is_featured = $request->is_featured;
                $post->save();

                $post->post_categories()->delete();
                foreach (explode(',', $request->category_ids) as $categoryId) {
                    $post->post_categories()->create([
                        "category_id" => $categoryId
                    ]);
                }

                $post->post_tags()->delete();
                foreach (explode(',', $request->tag_ids) as $tagId) {
                    $post->post_tags()->create([
                        "tag_id" => $tagId
                    ]);
                }

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
