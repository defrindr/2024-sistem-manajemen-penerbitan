<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Fantasi',
            'Sci-fi',
            'Horor',
            'Misteri',
            'Romantis',
        ];

        foreach ($categories as $category) {
            Kategori::create(['name' => $category]);
        }
    }
}
