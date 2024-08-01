<?php

namespace Database\Seeders;

use App\Models\Ebook;
use App\Models\EbookReview;
use App\Models\Kategori;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Romansa', 'Olahraga', 'Teknologi'];


        foreach ($categories as $category) Kategori::create(['name' => $category]);


        $topics = [
            [
                'topic' => 'Cinta Sekolah',
                'sub' => [
                    'Judul ABC',
                    'Judul DEF',
                    'Judul GHI',
                ]
            ],
            [
                'topic' => 'Kehidupan yang susah',
                'sub' => [
                    'Judul ABC',
                    'Judul DEF',
                    'Judul GHI',
                ]
            ],
            [
                'topic' => 'Kehidupan malam',
                'sub' => [
                    'Judul ABC',
                    'Judul DEF',
                    'Judul GHI',
                ]
            ],
            [
                'topic' => 'Cerita perjuangan hidup',
                'sub' => [
                    'Judul ABC',
                    'Judul DEF',
                    'Judul GHI',
                ]
            ],
        ];

        $reviewers = User::where('roleId', Role::findIdByName(Role::REVIEWER))->get();

        foreach ($topics as $topic) {
            $theme = Theme::create([
                'categoryId' => Kategori::inRandomOrder()->first()->id,
                'name' => $topic['topic'],
                'dueDate' => date('Y-m-d'),
                'price' => random_int(10000, 100000),
                'description' => '-'
            ]);

            foreach ($topic['sub'] as $title) {
            }
        }
    }
}
