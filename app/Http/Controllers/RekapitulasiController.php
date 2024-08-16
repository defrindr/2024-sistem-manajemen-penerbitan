<?php

namespace App\Http\Controllers;

use App\Exports\RekapCetakanExport;
use App\Exports\RekapKeuanganExport;
use App\Models\Keuangan;
use App\Models\KeuanganDetail;
use App\Models\Publication;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RekapitulasiController extends Controller
{
    /**
     * Retrieves a paginated list of publications based on the search term.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\View\View The view for displaying the list of publications.
     */
    public function cetakan(Request $request)
    {
        $publicationQuery = Publication::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $publicationQuery->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('productionYear', 'like', "%{$searchTerm}%");
        }

        $publications = $publicationQuery->orderBy('created_at', 'desc')->paginate();

        return view('rekapitulasi.cetakan', compact('publications'));
    }

    public function keuangan(Request $request)
    {
        // Retrieve the necessary data for generating the rekapitulasi keuangan
        $financeQuery = Keuangan::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $financeQuery->where('title', 'like', "%{$searchTerm}%");
        }

        $finances = $financeQuery->orderBy('created_at', 'asc')->paginate();

        return view('rekapitulasi.keuangan', compact('finances'));
    }

    public function exportCetakan()
    {
        return Excel::download(new RekapCetakanExport(), 'rekapitulasi.cetakan.xlsx');
    }

    public function exportKeuangan()
    {
        return Excel::download(new RekapKeuanganExport(), 'rekapitulasi.keuangan.xlsx');
    }
}
