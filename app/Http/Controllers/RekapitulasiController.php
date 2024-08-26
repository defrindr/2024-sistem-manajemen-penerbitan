<?php

namespace App\Http\Controllers;

use App\Exports\RekapCetakanExport;
use App\Exports\RekapKeuanganExport;
use App\Models\Keuangan;
use App\Models\Publication;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class RekapitulasiController extends Controller
{
    /**
     * Retrieves a paginated list of publications based on the search term.
     *
     * @param  Request  $request  The HTTP request object.
     * @return \Illuminate\View\View The view for displaying the list of publications.
     */
    public function cetakan(Request $request)
    {
        $publicationQuery = Publication::query()
            ->leftJoin('theme_recommendations', 'theme_recommendations.id', '=', 'publications.themeId')
            ->leftJoin('ebooks', 'ebooks.themeId', '=', 'theme_recommendations.id')
            ->leftJoin('ebook_reviews', 'ebook_reviews.ebookId', '=', 'ebooks.id');

        if (! in_array(Auth::user()->role->name, [Role::ADMINISTRATOR, Role::SUPERADMIN])) {
            $publicationQuery
                ->where(function ($query) {
                    if (Auth::user()->role->name == Role::AUTHOR) {
                        $query->where('ebooks.userId', Auth::id());
                    } else {
                        $query->where('ebook_reviews.reviewerId', Auth::id());
                    }
                });
        }

        $publicationQuery->groupBy(
            'publications.id',
            'publications.themeId',
            'publications.title',
            'publications.cover',
            'publications.numberOfPrinting',
            'publications.productionYear',
            'publications.totalProduction',
            'publications.price'
        );

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $publicationQuery->where('publications.title', 'like', "%{$searchTerm}%")
                ->orWhere('publications.productionYear', 'like', "%{$searchTerm}%");
        }

        $publications = $publicationQuery->select(
            'publications.id',
            'publications.themeId',
            'publications.title',
            'publications.cover',
            'publications.numberOfPrinting',
            'publications.productionYear',
            'publications.totalProduction',
            'publications.price'
        )->orderBy('publications.created_at', 'desc')->paginate();

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
        return Excel::download(new RekapCetakanExport, 'rekapitulasi.cetakan.xlsx');
    }

    public function exportKeuangan()
    {
        return Excel::download(new RekapKeuanganExport, 'rekapitulasi.keuangan.xlsx');
    }
}
