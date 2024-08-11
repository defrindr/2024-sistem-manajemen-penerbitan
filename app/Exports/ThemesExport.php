<?php

namespace App\Exports;

use App\Models\Theme;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ThemesExport implements FromView
{
    public function view(): View
    {
        return view('exports.themes', [
            'pagination' => Theme::all()
        ]);
    }
}
