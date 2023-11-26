<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Event;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
      $CommentEventTheatre = Event::where('name', 'Theatre Performance')->first();
      $creatorDoe = User::where('name', 'John Doe')->first();
      $creatorFranta = User::where('name', 'Franta Bezny')->first();

        $data1 = [
            'event_id' => $CommentEventTheatre->id,
            'user_id' => $creatorDoe->id,
            'body' => 'Velice prumerne',
            'rating' => 3
        ];

        if (Comment::where('user_id',$data1['user_id'])->count() == 0)
        {
            $comment = Comment::create($data1);
        }


        $data2 = [
          'event_id' => $CommentEventTheatre->id,
          'user_id' => $creatorFranta->id,
          'body' => 'Moc jsem si to užil',
          'rating' => 5
        ];

        if (Comment::where('user_id',$data2['user_id'])->count() == 0)
        {
          $comment = Comment::create($data2);
        }
    }
}
