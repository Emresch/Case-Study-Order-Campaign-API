<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class authors extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jasonPath = database_path('database\data\authors.json');
        $jasonContent = File::get($jasonPath);
        $authors = json_decode($jasonContent, true);
        foreach ($authors as $author) {
            DB::table('authors')->insert([
                'author_id' => $author['author_id'],
                'author_name' => $author['author_name'],
            ]);

        }
    }
}
