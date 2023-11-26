<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ModeratorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $data = [
            'name' => 'moderator',
            'email' => 'moderator@moderator.com',
            'password' => Hash::make('MODERATOR')
        ];

        if (User::where('email',$data['email'])->count() == 0)
        {
            $user = User::create($data);
            $role = RoleEnum::getModerator();
            $user->roles()->attach($role);
        }
    }
}
