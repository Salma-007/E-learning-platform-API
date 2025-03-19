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

    public function update(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => 'Utilisateur non authentifié', 'headers' => request()->headers->all()], 401);
            }
            
            // if ($request->user()->id !== $user->id) {
            //     return response()->json(['error' => 'Action non autorisée'], 403);
            // }

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

    public function updateUser(Request $request, User $user)
    {
        try {
            // $a = $user->role();
            // return response()->json([$a]);
            $userAuth = auth()->user();

            if (!$userAuth) {
                return response()->json(['error' => 'Utilisateur non authentifié', 'headers' => request()->headers->all()], 401);
            }
            
            // if ($request->user()->id !== $user->id) {
            //     return response()->json(['error' => 'Action non autorisée'], 403);
            // }

            if(!$user->isAdmin()){
                return response()->json(['error' => 'Vous n \'avez pas role Admin']);
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
