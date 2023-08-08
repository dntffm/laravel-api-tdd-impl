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
            return $this->sendError($validate->errors(),422);
        }

        $userExist = User::where('email', '=', $request->email)->exists();

        if($userExist) {
            return $this->sendError('Email already exists', 500);
        }

        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);

        return $this->sendResponse('Registration Success', 200);
    }

    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validated->fails()) {
            return $this->sendError($validated->errors(), 422);
        }

        $who = User::where('email', $request->email);

        if(! $who->exists()) {
            return $this->sendError('Credential not found!', 404);
        }

        $isValid = Hash::check($request->password, $who->first()->password);

        if(!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong password!'
            ], 403);
        }

        $user = $who->first();
        $token = $user->createToken('token-ts');

        $data['token'] = $token->plainTextToken;
        $data['user'] = $user;

        return $this->sendResponse('Login successfully!', $data);
    }

    public function logout()
    {
        if(Auth::check()) {
            $userToken = Auth::user()->tokens();
            $userToken->delete();

            return $this->sendResponse('Logout successfull!', 200);
        }
    }
}
