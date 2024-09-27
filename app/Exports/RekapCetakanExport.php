<?php

namespace App\Exports;

use App\Models\Publication;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapCetakanExport implements FromView
{
    public function view(): View
    {

        $query = Publication::query();
        if (request()->filled('search')) {
            $searchTerm = request()->input('search');
            $query->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('productionYear', 'like', "%{$searchTerm}%");
        }

        return view('exports.rekap-cetakan', [
            'paginations' => $query->get(),
        ]);
    }
}
