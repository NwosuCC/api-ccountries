<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * If true, the User is logged in after successful registration
     */
    private static $auto_login = false;


    public function __construct()
    {
    }


    public function register(Request $request)
    {
      $this->validator($request->all())->validate();

      $user = $this->create($request->all());

      if(static::$auto_login) {
        auth()->guard()->login($user);
        $user = auth()->user();
      }

      return response()->json($user, 200);
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['date', 'before:'.User::minBirthDate()],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'alpha_num', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);
    }


    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'date_of_birth' => $data['date_of_birth'],
            'email' => $data['email'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
