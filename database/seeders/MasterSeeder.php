<?php

namespace Database\Seeders;

use App\Models\Ebook;
use App\Models\EbookReview;
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
        $topics = [
            [
                'topic' => 'Cinta Sekolah',
                'titles' => [
                    'Judul ABC',
                    'Judul DEF',
                    'Judul GHI',
                ]
            ],
            [
                'topic' => 'Kehidupan yang susah',
                'titles' => [
                    'Judul ABC',
                    'Judul DEF',
                    'Judul GHI',
                ]
            ],
            [
                'topic' => 'Kehidupan malam',
                'titles' => [
                    'Judul ABC',
                    'Judul DEF',
                    'Judul GHI',
                ]
            ],
            [
                'topic' => 'Cerita perjuangan hidup',
                'titles' => [
                    'Judul ABC',
                    'Judul DEF',
                    'Judul GHI',
                ]
            ],
        ];

        $reviewers = User::where('roleId', Role::findIdByName(Role::REVIEWER))->get();

        foreach ($topics as $topic) {
            $theme = Theme::create([
                'name' => $topic['topic'],
                'dueDate' => date('Y-m-d'),
                'description' => '-'
            ]);

            foreach ($topic['titles'] as $title) {
                $ebook = Ebook::create([
                    'themeId' => $theme->id,
                    'title' => $title,
                    'draft' => '',
                    'userId' => User::where('roleId', Role::findIdByName(Role::AUTHOR))
                        ->inRandomOrder()
                        ->first()
                        ->id
                ]);


                // foreach ($reviewers as $review) {
                //     $ebookReview = EbookReview::create([
                //         'ebookId' => $ebook->id,
                //         'reviewerId' => $review->id,
                //         'acc' => [-1, 1][random_int(0, 1)]
                //     ]);
                // }

                // $jumlahAcc = $ebook->reviews()->where('acc', 1)->count();
                // $jumlahReject = $ebook->reviews()->where('acc', -1)->count();

                // if ($jumlahAcc > $jumlahReject) {
                //     $ebook->update(['status' => Ebook::STATUS_PUBLISH]);
                // } else {
                //     $ebook->update(['status' => Ebook::STATUS_NOT_ACCEPT]);
                // }
            }
        }
    }
}
