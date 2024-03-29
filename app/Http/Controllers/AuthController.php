<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                return response()->json(["status" => 401, "error" => 1, "message" => "Unauthorized access"], 200);
            }
        } catch (\Throwable $th) {
            Log::error("500 => AuthController => CheckExpiration => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }

    /* public function adminLogin(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = User::find(Auth::id());
                $token = $user->createToken('auth_token')->plainTextToken;
                Log::info("User Login Successfully.");
                return response()->json(['status' => 200, 'token' => $token]);
            } else {
                return response()->json(['status' => 401, 'message' => "User Not Found."]);
            }
        } catch (\Throwable $th) {
            Log::error("500 => AuthController => CheckExpiration => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    } */

    public function adminLogin(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $user = User::find(Auth::id());
                $token = $user->createToken('auth_token')->plainTextToken;
                if ($token) {
                    Log::info("Token created successfully for user: " . $user->email);
                    return response()->json(['status' => 200, 'token' => $token]);
                } else {
                    Log::error("Token creation failed for user: " . $user->email);
                    return response()->json(['status' => 500, 'message' => "Token creation failed."]);
                }
            } else {
                return response()->json(['status' => 401, 'message' => "User Not Found."]);
            }
        } catch (\Throwable $th) {
            Log::error("500 => AuthController => AdminLogin => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }
    
    public function adminLogout(Request $request)
    {
        try {
            Auth::logout();
            Log::info("User Logout Successfully.");
            return response()->json(['status' => 200 , 'message' => 'User Logout Successfully.']);
        } catch (\Throwable $th) {
            Log::error("500 => AuthController => CheckExpiration => " . $th);
            return response()->json(["status" => 500, "error" => 1, "message" => "Getting Some Error, Please Try Again."], 500);
        }
    }
}
