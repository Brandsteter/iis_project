<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
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
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('ADMIN')
        ];

        if (User::where('email',$data['email'])->count() == 0)
        {
            $user = User::create($data);
            $role = RoleEnum::getAdmin();
            $user->roles()->attach($role);
        }
    }
}
