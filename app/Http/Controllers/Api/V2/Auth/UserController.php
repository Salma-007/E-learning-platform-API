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

    
}
