<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function register(UserRegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): UserResource
    {
        $data = $request->validated();
        $credentials = request(['email', 'password']);
        $token = auth()->attempt($credentials);
        if (!$token)
            throw new HttpResponseException (response([
                'errors' => [
                    'message' => ['username or password wrong'],
                    ]
                ],401));
                
        $user = User::where('email', $data['email'])->first();
        $user->remember_token = Str::uuid()->toString();
        $user->save();
        return new UserResource($user);
    }

    public function get(Request $request): UserResource
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request): UserResource
    {
        $data = $request->validated();
        $user = Auth::user();

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        $user->save();
        return new UserResource($user);
    }
    
    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user->remember_token = null;
        $user->save();
        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }

    public function getContacts(Request $request): JsonResponse
    {
        // Get the authenticated user
        $user = Auth::user();

        // Eager load contacts for the user
        $user->load('contacts');

        // Return user data with contacts
        return response()->json([
            "data" => $user
        ])->setStatusCode(200);
    }

    public function refresh(Request $request)
    {
    $request->user()->tokens()->delete();

    $token = $request->user()->createToken('authToken')->plainTextToken;

    return response()->json(['token' => $token], 200);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
