<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function guestUserCreateToken()
    {
        $token = User::where('is_guest', 1)->first()->createToken('login', [], now()->addHour());
        return ['token' => $token->plainTextToken];
    }

    public function adminCreateToken()
    {
        $token = User::where('is_admin', 1)->first()->createToken('login', ['*'], now()->addHour());
        return ['token' => $token->plainTextToken];
    }
}
