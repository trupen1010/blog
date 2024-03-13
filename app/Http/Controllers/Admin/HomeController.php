<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        try {
            if (auth()->guard('sanctum')->check()) {
                $data['authorCount'] = Author::count();
                $data['categoryCount'] = Category::count();
                $data['tagCount'] = Tag::count();
                $data['postCount'] = Post::count();
                return response()->json(["status" => 200, "error" => 0, "message" => "Get Data Successfully", "data" => $data], 200);
            }
            return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
        } catch (\Throwable $th) {
            Log::error("500 => HomeController => Index => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }
}
