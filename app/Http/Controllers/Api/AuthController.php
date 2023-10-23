<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['The provided credential are incorrect.']
            ]);
        }

        // If those values don't match, this would return false
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credential are incorrect.']
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;
    
        return response()->json([
            'token' => $token
        ]);


    }

    public function logout(Request $request)
    {

    }
}
