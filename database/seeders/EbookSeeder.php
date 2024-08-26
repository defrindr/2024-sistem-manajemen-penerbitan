<?php

namespace Database\Seeders;

use App\Models\Ebook;
use App\Models\EbookReview;
use App\Models\Role;
use App\Models\SubTheme;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Seeder;

class EbookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sts = SubTheme::get();
        foreach ($sts as $st) {
            $theme = $st->theme;
            $subThemes = $theme->subThemes;
            $author = User::where(['roleId' => Role::findIdByName(Role::AUTHOR)])->first();

            $reviewer1Id = $theme->reviewer1Id;
            $reviewer2Id = $theme->reviewer2Id;

            foreach ($subThemes as $subTheme) {
                $ebook = Ebook::create([
                    'themeId' => $theme->id,
                    'subthemeId' => $subTheme->id,
                    'userId' => $author->id,
                    'title' => $subTheme->name . ' - ' . $theme->name,
                    'draft' => 'Revisi_1722751630.docx',
                    'proofOfPayment' => '007_Defri Indra Mahardika_1722708279.pdf',
                    'royalty' => null,
                    'status' => Ebook::STATUS_ACCEPT,
                ]);

                EbookReview::create([
                    'ebookId' => $ebook->id,
                    'reviewerId' => $reviewer1Id,
                    'acc' => 1,
                ]);

                EbookReview::create([
                    'ebookId' => $ebook->id,
                    'reviewerId' => $reviewer2Id,
                    'acc' => 1,
                ]);
            }

            $theme->update(['status' => Theme::STATUS_CLOSE]);
        }
    }
}
