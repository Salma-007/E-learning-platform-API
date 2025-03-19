<?php

namespace App\Http\Controllers\Api\V2\Auth;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

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
                    'role' => 'required|string|in:student,mentor', 
                ]);
            
            $role = Role::where('name', $data['role'])->first();

            if (!$role) {
                return response()->json(['error' => 'Rôle invalide.'], 400);
            }

            $data['role_id'] = $role->id;
            
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

    public function logout()
    {
        try {

            $this->authService->logout();

            return response()->json(['message' => 'Logged out successfully']);
            
        } catch (Exception $e) {

            return response()->json(['error' => 'An error occurred during logout.'], 500);
        }
    }   
}

