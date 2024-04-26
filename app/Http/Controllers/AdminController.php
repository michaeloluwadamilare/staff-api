<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    //

    public function store(Request $request)
    {

        try {
            // Validate the incoming request data
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            // Return a JSON response with validation errors and status code 422
            return response()->json([
                'status' => 'error',
                'error' => 'Validation Error',
                'message' => $e->validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'user' => $user
        ], Response::HTTP_CREATED);


    }

    public function login(Request $request)
    {
        try {
            // Validate the incoming request data
            $request->validate([
                'email' => ['required', 'email'],
                'password' => 'required',
            ]);
        } catch (ValidationException $e) {
            // Return a JSON response with validation errors and status code 422
            return response()->json([
                'status' => 'error',
                'error' => 'Validation Error',
                'message' => $e->validator->errors()->first(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'status' => 'error',
                'message' => 'Incorrect login details.',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'user logged out'
        ],200);
    }

}
