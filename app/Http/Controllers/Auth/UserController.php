<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use App\Models\User;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required_without_all:email,username',
            'username' => 'required_without_all:email,phone',
            'email' => 'required_without_all:phone,username',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('phone', $request->phone)
            ->orWhere('email', $request->email)
            ->orWhere('username', $request->username)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user_id' => $user->id
        ], 200);

    }

    /**
     * Destroy the user token
     * Revoke the user's current token...
     * @return void
     */
    public function logout(Request $request)
    {
        $request->user()->currentaccesstoken()->delete();

        return response()->json([
            'message' => 'token deleted!'
        ], 201);
    }
    
    /**
     * Register a New user
     *
     */
    public function register(Request $request)
    {
       $request->validate([
        'name' => 'required|max:255',
        'username' => 'required|max:255|min:4|unique:users,username',
        'phone' => 'required|max:11|min:11|unique:users,phone',
        'email' => 'required|email:rfc|unique:users,email',
        'password' => 'required|confirmed|min:8'
        ]);

        $data = $request->all();
        $data['password'] = \Hash::make($request->password);
        $user = User::create($data);

        // make a profile corresponding to the new user
        $user->profile()->create();

        return response()->json([
            'message' => "User Created, GG WP!"
        ], 200);
    }

    /**
     * Return a user with the current user token
     *
     */
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ], 200);
    }
    
    
    
}


