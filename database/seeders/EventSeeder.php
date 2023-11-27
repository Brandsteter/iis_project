<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Place;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
      $eventPlaceSemilasso = Place::where('name', 'Semilasso')->first();
      $eventPlaceFit = Place::where('name', 'VUT FIT')->first();
      $creatorAdmin = User::where('name', 'admin')->first();
      $categoryCulture = Category::where('name', 'Culture')->first();
      $categoryBlues = Category::where('name', 'Blues')->first();

        $data1 = [
            'name' => 'Theatre Performance',
            'capacity_max' => null,
            'capacity_current' => 3,
            'is_approved' => true,
            'description' => 'Dramatical theatre performance',
            'event_start' => '2023-11-23',
            'event_end' => '2023-11-23',
            'event_start_time' => '17:00:00',
            'event_end_time' => '20:00:00',
            'place_id' => $eventPlaceSemilasso->id,
            'creator_user_id' => $creatorAdmin->id,
        ];

        if (Event::where('name',$data1['name'])->count() == 0)
        {
            $event = Event::create($data1);
            $event->categories()->attach($categoryCulture);
            $event->users()->attach($creatorAdmin);
        }


        $data2 = [
          'name' => 'Music festival',
          'capacity_max' => null,
          'capacity_current' => 2,
          'is_approved' => true,
          'description' => 'Blues music festival',
          'event_start' => '2024-01-12',
          'event_end' => '2024-01-14',
          'event_start_time' => null,
          'event_end_time' => null,
          'place_id' => $eventPlaceFit->id,
          'creator_user_id' => $creatorAdmin->id,
        ];

        if (Event::where('name',$data2['name'])->count() == 0)
        {
          $event = Event::create($data2);
          $event->categories()->attach($categoryBlues);
        }

        $data3 = [
          'name' => 'School party',
          'capacity_max' => 150,
          'is_approved' => false,
          'description' => 'A party for all introvert IT students',
          'event_start' => '2024-01-12',
          'event_end' => '2024-01-13',
          'event_start_time' => '16:00:00',
          'event_end_time' => '05:00:00',
          'place_id' => $eventPlaceFit->id,
          'creator_user_id' => $creatorAdmin->id,
        ];

        if (Event::where('name',$data3['name'])->count() == 0)
        {
          $event = Event::create($data3);
        }


    }
}
