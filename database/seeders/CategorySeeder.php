<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CategorySeeder extends Seeder
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
            'name' => 'Culture',
            'parent_category_id' => null,
            'is_approved' => true
        ];

        if (Category::where('name',$data1['name'])->count() == 0)
        {
            $category = Category::create($data1);
        }

        $parentCategoryCulture = Category::where('name', 'Culture')->first();

        $data2 = [
          'name' => 'Music',
          'parent_category_id' => $parentCategoryCulture->id,
          'is_approved' => true
        ];

        if (Category::where('name',$data2['name'])->count() == 0)
        {
          $category = Category::create($data2);
        }

        $parentCategoryMusic = Category::where('name', 'Music')->first();

        $data3 = [
          'name' => 'Blues',
          'parent_category_id' => $parentCategoryMusic->id,
          'is_approved' => true
        ];

        if (Category::where('name',$data3['name'])->count() == 0)
        {
          $category = Category::create($data3);
        }

        $data4 = [
          'name' => 'Art',
          'parent_category_id' => $parentCategoryCulture->id,
          'is_approved' => true
        ];

        if (Category::where('name',$data4['name'])->count() == 0)
        {
          $category = Category::create($data4);
        }

        $data5 = [
          'name' => 'Seminars',
          'parent_category_id' => null,
          'is_approved' => false
        ];

        if (Category::where('name',$data5['name'])->count() == 0)
        {
          $category = Category::create($data5);
        }

    }
}
