<?php
namespace App\Repositories;

use Exception;
use Throwable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
        try{
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);
            $token = $user->createToken("API TOKEN")->plainTextToken;
            return ['user' => $user, 'token' => $token];

        } catch (\Throwable $th) {
            throw new \Exception("Erreur lors de register : " . $th->getMessage());
        }

    }

    public function login(array $credentials)
    {
        try{
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('auth-token')->plainTextToken;
                return ['user' => $user, 'token' => $token];
            }
            return null;
        } catch (Throwable $th) {
            throw new Exception("Erreur lors de login : " . $th->getMessage());
        }

    }

    public function logout()
    {
        try {
            Auth::logout();

            if (Auth::check()) {
                Auth::user()->tokens()->delete(); 
            }

            return true;

        } catch (\Throwable $th) {
            throw new \Exception("Erreur lors de la déconnexion : " . $th->getMessage());
        }
    }
}