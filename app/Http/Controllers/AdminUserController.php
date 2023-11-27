<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function adminPage()
    {
        return view('admin');
    }
    /**
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'passwordRepeat' => ['required', 'same:password'],
            'role' => ['required', 'string']
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $user->roles()->attach(RoleEnum::fromString($data['role'])->getRole());

        return response([
            'message' => 'User successfully created'
        ], 201);
    }

    public function delete(User $user)
    {
        if ($user->isRole(RoleEnum::Admin))
        {
            return response([
                'message' => 'This user is admin'
            ], 400);
        }

        $userEvents = Event::where('creator_user_id', $user->id)->get();

        foreach ($userEvents as $event) {
            $event->update(['creator_user_id' => null]);
        }

        $user->delete();
        return response()->noContent();
    }

    /**
     * @throws \Exception
     */
    public function update(User $user, Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id), 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'passwordRepeat' => ['nullable', 'same:password'],
            'role' => ['required', 'string']
        ]);

        if ($user->isRole(RoleEnum::Admin) && !$user->isRole(RoleEnum::fromString($data['role'])))
        {
            return response([
                'message' => 'This user is admin, role cannot be changed'
            ], 400);
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->roles()->detach();
        $user->roles()->attach(RoleEnum::fromString($data['role'])->getRole());

        $user->update($data);

        return response([
            'message' => 'User successfully updated'
        ], 201);
    }

    public function getAllUsers()
    {
        $users = User::with('roles')->paginate(5);

        return response()->json($users);
    }
}
