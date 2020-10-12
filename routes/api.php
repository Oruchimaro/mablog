<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/posts', PostController::class);

// temp token generator for api
Route::post('/sanctum/token', function (Request $request) {
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
    ]);
});


// Revoke the user's current token...
Route::middleware('auth:sanctum')->delete('/sanctum/destroy/token', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        'message' => 'Token Deleted!',
        'status' => '201'
    ]);
});
