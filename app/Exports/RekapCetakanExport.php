<?php

namespace App\Exports;

use App\Models\Publication;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class RekapCetakanExport implements FromView
{
    public function view(): View
    {
        return view('exports.rekap-cetakan', [
            'paginations' => Publication::get()
        ]);
    }
}
