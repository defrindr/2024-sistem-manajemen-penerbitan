<?php

namespace App\Exports;

use App\Models\Keuangan;
use App\Models\KeuanganDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class RekapKeuanganExport implements FromView
{
    public function view(): View
    {
        return view('exports.rekap-keuangan', [
            'pagination' => KeuanganDetail::get()
        ]);
    }
}
