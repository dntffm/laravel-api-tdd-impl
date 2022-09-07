<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'  => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validate->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Registration Failed',
                'errors' => $validate->errors(),
            ], 422);
        }

        $userExist = User::where('email', '=', $request->email)->exists();

        if($userExist) {
            return response()->json([
                'status' => false,
                'message' => 'Email already exists',
            ], 500);
        }

        $request->password = Hash::make($request->password);

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registration Success',
            'user' => $user,
        ], 200);
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validated->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validated->errors()
            ], 422);
        }

        $who = User::where('email', $request->email);

        if(! $who->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Credential not found!'
            ], 404);
        }

        $checkValidity = Hash::check($request->password, $who->first()->password);

        if($checkValidity) {
            $user = $who->first();
            $token = $user->createToken('token-ts');

            $data['token'] = $token->plainTextToken;
            $data['user'] = $user;

            return response()->json([
                'success' => true,
                'message' => 'Login successfull!',
                'data' => $data
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Wrong password!'
        ], 403);
    }

    public function logout()
    {
        if(Auth::check()) {
            $userToken = Auth::user()->tokens();
            $userToken->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successfull!',
            ], 200);
        }
    }
}
