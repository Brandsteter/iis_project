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

        return response([
            'message' => 'Successfully registered'
        ], 201);
    }

    public function registerPage()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']]))
        {
            return response([
                'message' => 'wrong login'
            ],400);
        }

        $user = User::where('email', $data['email'])->first();
        Auth::login($user);

        $user = User::with('roles')->where('email', $data['email'])->first();

        return response()->json($user);
    }

    public function loginPage()
    {
        return view('auth.login');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/auth/login');
    }

    public function me()
    {
        if (Auth::check()) {
            return response([
                "data" => auth()->user()->load("roles"),
            ]);
        } else {
            return response([
                "error" => "User not logged in",
            ], 401);
        }
    }
}
