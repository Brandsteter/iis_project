<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Place;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $data1 = [
            'name' => 'Semilasso',
            'address' => 'Nové Semilasso, Palackého tř. 3048/124, 612 00 Brno-Královo Pole',
            'description' => 'Brněnské kulturní centrum',
            'is_approved' => 1
        ];

        if (Place::where('name',$data1['name'])->count() == 0)
        {
            $place = Place::create($data1);
        }

        $data2 = [
          'name' => 'Koleje Pod Palackého Vrchem',
          'address' => 'Kolejní 2, 612 00 Brno-Královo Pole',
          'description' => 'Studentské koleje',
          'is_approved' => 1
        ];

        if (Place::where('name',$data2['name'])->count() == 0)
        {
          $place = Place::create($data2);
        }

        $data3 = [
          'name' => 'VUT FIT',
          'address' => 'Božetěchova 2/1, 612 00 Brno-Královo Pole',
          'description' => 'Fakulta informačních technologií',
          'is_approved' => 1
        ];

        if (Place::where('name',$data3['name'])->count() == 0)
        {
          $place = Place::create($data3);
        }

        $data4 = [
          'name' => 'Hotel Vista',
          'address' => 'Hudcova 72, 621 00 Brno-Medlánky',
          'description' => 'Hotel Vista',
          'is_approved' => 0
        ];

        if (Place::where('name',$data4['name'])->count() == 0)
        {
          $place = Place::create($data4);
        }
    }
}
