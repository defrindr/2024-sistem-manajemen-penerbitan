<?php

namespace Database\Seeders;

use App\Models\Ebook;
use App\Models\Role;
use App\Models\SubTheme;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EbookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $st = SubTheme::inRandomOrder()->first();
        $theme = $st->theme;
        $subThemes = $theme->subThemes;
        $author = User::where(['roleId' => Role::findIdByName(Role::AUTHOR)])->first();

        foreach ($subThemes as $subTheme) {
            Ebook::create([
                'themeId' => $theme->id,
                'subthemeId' => $subTheme->id,
                'userId' => $author->id,
                'title' => $subTheme->name . " - " . $theme->name,
                'draft' => 'Revisi_1722751630.docx',
                'proofOfPayment' => '007_Defri Indra Mahardika_1722708279.pdf',
                'royalty' => null,
                'status' => Ebook::STATUS_ACCEPT,
            ]);
        }

        $theme->update(['status' => Theme::STATUS_CLOSE]);
    }
}
