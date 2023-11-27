<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $eventTheatre = Event::where('name', 'Theatre Performance')->first();
        $eventFestival = Event::where('name', 'Music Festival')->first();

        $data1 = [
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => Hash::make('USER123')
        ];

        if (User::where('email',$data1['email'])->count() == 0)
        {
            $user = User::create($data1);
            $role = RoleEnum::getUser();
            $user->roles()->attach($role);
            $user->events()->attach($eventTheatre);
            $user->events()->attach($eventFestival);
        }

      $data2 = [
        'name' => 'S0zil5',
        'email' => 'vairenao@mail.com',
        'password' => Hash::make('USER123')
      ];

      if (User::where('email',$data2['email'])->count() == 0)
      {
        $user = User::create($data2);
        $role = RoleEnum::getUser();
        $user->roles()->attach($role);
        $user->events()->attach($eventTheatre);
        $user->events()->attach($eventFestival);
      }
    }
}
