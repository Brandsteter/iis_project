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
      $creatorUser = User::where('name', 'user')->first();
      $creatorMiro = User::where('name', 'S0zil5')->first();

        $data1 = [
            'event_id' => $CommentEventTheatre->id,
            'user_id' => $creatorUser->id,
            'body' => 'Decent event',
            'rating' => 3
        ];

        if (Comment::where('user_id',$data1['user_id'])->count() == 0)
        {
            $comment = Comment::create($data1);
        }


        $data2 = [
          'event_id' => $CommentEventTheatre->id,
          'user_id' => $creatorMiro->id,
          'body' => 'Good event',
          'rating' => 5
        ];

        if (Comment::where('user_id',$data2['user_id'])->count() == 0)
        {
          $comment = Comment::create($data2);
        }
    }
}
