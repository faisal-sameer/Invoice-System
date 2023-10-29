<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected function login(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'password' => 'required|min:8',
        ]);
        if ($validator->failed()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 200);
        }

        $credentials = request(['id', 'password']);

        $token =       auth('api')->attempt($credentials);
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 200);
        }



        return response()->json([
            'success' => true,
            'access_token' => $token,
            'userInfo' => auth('api')->user(),
            'expire_in' => auth('api')->factory()->getTTL(),
        ], 200);
    }
    protected function CheckLogin()
    {

        if (Auth::guard('api')->check()) {
            return response()->json([
                'success' => true,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
            ], 200);
        }
    }
}
