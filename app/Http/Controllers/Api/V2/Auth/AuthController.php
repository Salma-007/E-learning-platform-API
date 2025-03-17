<?php

namespace App\Http\Controllers\Api\V2\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6',
            ]);

            $user = $this->authService->register($data);

            return response()->json([
                'user' => $user['user'],
                'token' => $user['token'],
            ], 201);

        } catch (ValidationException $e) {

            return response()->json(['error' => $e->errors()], 422);

        } catch (Exception $e) {

            return response()->json(['error' => 'An error occurred during registration.'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
    
            $result = $this->authService->login($credentials);
    
            if ($result) {
                return response()->json($result);
            }
    
            return response()->json(['error' => 'Invalid credentials'], 401);

        } catch (ValidationException $e) {
            
            return response()->json(['error' => $e->errors()], 422);

        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred during login.'], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->user());

            return response()->json(['message' => 'Logged out successfully']);
            
        } catch (Exception $e) {

            return response()->json(['error' => 'An error occurred during logout.'], 500);
        }
    }   
}

