<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (RoleEnum::cases() as $role)
        {
            if (Role::where('role',$role->name)->count() == 0)
            {
                Role::create(['role' => $role->name]);
            }
        }
    }
}
