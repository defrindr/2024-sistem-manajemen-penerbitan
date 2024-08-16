<?php

namespace App\Http\Controllers;

use App\Exports\RekapCetakanExport;
use App\Exports\RekapKeuanganExport;
use App\Models\Keuangan;
use App\Models\KeuanganDetail;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RekapitulasiDetailController extends Controller
{
    public function keuangan(Request $request)
    {
        // Retrieve the necessary data for generating the rekapitulasi keuangan
        $financeQuery = KeuanganDetail::query()
            ->join('keuangans', 'keuangans.id', '=', 'keuangan_details.keuanganId')
            ->where('userId', Auth::user()->id);

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $financeQuery->where('keuangans.title', 'like', "%{$searchTerm}%");
        }

        $pagination = $financeQuery->orderBy('keuangans.created_at', 'asc')->paginate();

        return view('rekapitulasi-detail.keuangan', compact('pagination'));
    }

    public function exportKeuangan()
    {
        return Excel::download(new RekapKeuanganExport(
            KeuanganDetail::with('keuangan')
                ->where('userId', Auth::user()->id)
                ->get()
        ), 'rekapitulasi.keuangan.xlsx');
    }
}
