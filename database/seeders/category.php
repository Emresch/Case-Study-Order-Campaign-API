<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class category extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jasonPath = database_path('database\data\categories.json');
        $jasonContent = File::get($jasonPath);
        $categories = json_decode($jasonContent, true);
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'category_id' => $category['category_id'],
                'category_title' => $category['category_title'],
            ]);

        }

    }
}
