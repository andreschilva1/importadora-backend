<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::find(Auth::user()->id); 
            $token = $user->createToken('API TOKEN')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'rol'=> $user->getRoleNames()->first(),
                    'celular' => $user->celular,
                    'photo_path' => $user->profile_photo_path,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }
    }

    public function logout(Request $request)
    {   

        $user = $request->user();
        
        //$user->token_android = null;
        $user->tokens()->delete();

        //Auth::user()->Passport::tokensExpireIn(Carbon::now()->addDays(15));
        return response()->json([
            'status' => 1,
            'message' => 'Successfully logged out'
        ]);
    }
}
