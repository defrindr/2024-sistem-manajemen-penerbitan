<?php

namespace App\Exports;

use App\Models\SubTheme;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SubThemesExport implements FromView
{
    protected $theme;

    public function __construct($theme)
    {
        $this->theme = $theme;
    }

    public function view(): View
    {
        return view('exports.sub-themes', [
            'pagination' => SubTheme::where('themeId', $this->theme->id)->get(),
            'theme' => $this->theme,
        ]);
    }
}
