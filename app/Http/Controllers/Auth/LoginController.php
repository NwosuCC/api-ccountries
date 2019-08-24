<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class LoginController extends Controller
{
    use HasApiTokens, Notifiable;

    public function login()
    {
        if(auth()->attempt(request(['email', 'password']))){

            $user = auth()->user();

            $user->token = $user->createToken(User::tokenName())->accessToken;

            return response()->json($user, 200);
        }

        $data = [
            "error" => "Incorrect username or password"
        ];

        return response()->json($data, 401);
    }
}
