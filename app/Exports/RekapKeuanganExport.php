<?php

namespace App\Exports;

use App\Models\KeuanganDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapKeuanganExport implements FromView
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    public function view(): View
    {
        $financeQuery = KeuanganDetail::query()->join('keuangans', 'keuangans.id = keuangan_details.keuanganId')->select('keuangan_details.*');
        if (request()->filled('search')) {
            $searchTerm = request()->input('search');
            $financeQuery->where('keuangans.title', 'like', "%{$searchTerm}%");
        }

        return view('exports.rekap-keuangan', [
            'pagination' => $this->query ?? $financeQuery->get(),
        ]);
    }
}
