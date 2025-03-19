<?php

namespace App\Http\Controllers\Api\V2\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function show(User $user)
    {
        return response()->json([
            'user' => UserResource::make($user),  
            'message' => 'Profil utilisateur récupéré avec succès'
        ]);
    }

    public function update(Request $request, User $user)
    {
        try {
            if (!$request->user()) {
                return response()->json(['error' => 'Utilisateur non authentifié'], 401);
            }
            
            if ($request->user()->id !== $user->id) {
                return response()->json(['error' => 'Action non autorisée'], 403);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$user->id,
            ]);

            $user->update($validatedData);

            return response()->json([
                'user' => new UserResource($user),
                'message' => 'Profil mis à jour avec succès'
            ]);
    
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors()
            ], 422);
    
        }
    }

    
}
