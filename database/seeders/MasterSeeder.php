<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Role;
use App\Models\SubTheme;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            'Perjalanan waktu',
            'Dunia paralel',
            'Kecerdasan buatan',
            'Apokaliptik',
            'Superhero',
            'Fantasi',
            'Sci-fi',
            'Horor',
            'Misteri',
            'Romantis',
            'Sekolah',
            'Keluarga',
            'Pekerjaan',
            'Perjalanan',
            'Sejarah',
            'Masa depan',
            'Lingkungan',
            'Politik',
            'Agama',
            'Sosiologi',
            'Hewan yang bisa bicara',
            'Objek yang hidup',
            'Dunia tanpa warna',
            'Manusia yang bisa terbang',
            'Pulau yang terisolasi',
            'Kota bawah tanah',
            'Virtual reality yang terlalu nyata',
            'Perjalanan ke pusat bumi',
            'Pertemuan dengan alien',
            'Dunia yang dikuasai oleh tumbuhan',
            'Peri hutan yang melindungi kerajaan manusia',
            'Penyihir muda yang mencari kekuatan sejati',
            'Naga yang menjaga harta karun kuno',
            'Koloni manusia di Mars yang memberontak',
            'Robot yang jatuh cinta pada manusia',
            'Pesawat ruang angkasa yang hilang di galaksi',
            'Rumah tua yang menyimpan banyak rahasia gelap',
            'Hantu yang bergentayangan di sekolah tua',
            'Monster laut yang menyerang kapal nelayan',
            'Detektif swasta yang memecahkan kasus pembunuhan berantai',
            'Penulis novel misteri yang terlibat dalam kasus pembunuhan',
            'Arkeolog yang menemukan artefak kuno yang berbahaya',
            'Cinta pertama yang penuh lika-liku',
            'Pernikahan yang diatur yang berakhir dengan cinta sejati',
            'Cinta terlarang antara dua orang yang berbeda dunia',
            'Atlet muda yang berjuang meraih impiannya',
            'Band indie yang berusaha terkenal',
            'Seniman yang mencari inspirasi',
            'Chef muda yang menciptakan hidangan unik',
            'Guru yang menginspirasi murid-muridnya',
        ];

        $subtopics = [
            'SUBTOPIC ABC',
            'SUBTOPIC DEF',
            'SUBTOPIC GHI',
        ];

        foreach ($topics as $topic) {
            $categoryId = Kategori::inRandomOrder()->first()->id;
            $theme = Theme::create([
                'categoryId' => $categoryId,
                'name' => $topic,
                'price' => random_int(10000, 100000),
                'description' => '-',
                'reviewer1Id' => self::findUserWithCategory($categoryId),
                'reviewer2Id' => self::findUserWithCategory($categoryId),
                'dueDate' => date('Y-m-d H:i:s', strtotime("+2 hours")),
            ]);

            foreach ($subtopics as $title) {
                $randInt = random_int(99, 990);
                SubTheme::create([
                    'themeId' => $theme->id,
                    'name' => $title,
                    'dueDate' => date('Y-m-d H:i:s', strtotime("+ $randInt minutes")),
                ]);
            }
        }
    }

    /**
     * Find user with role Reviewer & specific category Id
     *
     * @return null|\App\Models\User
     */
    public static function findUserWithCategory($categoryId)
    {
        $user = User::where(['roleId' => Role::findIdByName(Role::REVIEWER), 'categoryId' => $categoryId])->inRandomOrder()->select('id')->first();
        if (!$user) {
            return null;
        }

        return $user->id;
    }
}
