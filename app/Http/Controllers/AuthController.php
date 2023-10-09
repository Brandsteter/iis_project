<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @throws Exception
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'passwordRepeat' => ['required', 'same:password']
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $role = RoleEnum::getUser();
        $user->roles()->attach($role);

        Auth::login($user);
    }

    public function registerPage()
    {
        return view('auth.register');
    }
}
