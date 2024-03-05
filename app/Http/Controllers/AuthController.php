<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function guestUserCreateToken()
    {
        try {
            $token = User::where('is_guest', 1)->first()->createToken('login', [], now()->addHour());
            return ['token' => $token->plainTextToken];
        } catch (\Throwable $th) {
            Log::error("500 => AuthController => GuestUserCreateToken => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    public function adminCreateToken()
    {
        try {
            $token = User::where('is_admin', 1)->first()->createToken('login', ['*'], now()->addHour());
            return ['token' => $token->plainTextToken];
        } catch (\Throwable $th) {
            Log::error("500 => AuthController => AdminCreateToken => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    public function checkExpiration()
    {
        try {
            if (auth()->guard('sanctum')->check()) {
                return response()->json(["status" => 200, "error" => 0, "message" => "Token Authenticated Successfully."], 200);
            } else {
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 401);
            }
        } catch (\Throwable $th) {
            Log::error("500 => AuthController => CheckExpiration => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }
}
