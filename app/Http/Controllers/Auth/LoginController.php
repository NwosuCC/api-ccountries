<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class LoginController extends Controller
{
    use HasApiTokens, Notifiable;

    public function __construct()
    {
    }

    public function login()
    {
      if(auth()->attempt(request(['email', 'password']))){

        $user = auth()->user();

        $user->token = $user->createToken(User::tokenName())->accessToken;

        return response()->json($user, 200);
      }

      return abort(401, "Incorrect username or password");
    }
}
