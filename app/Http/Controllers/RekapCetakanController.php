<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;

class RekapCetakanController extends Controller
{
    /**
     * Retrieves a paginated list of publications based on the search term.
     *
     * @param Request $request The HTTP request object.
     * @return \Illuminate\View\View The view for displaying the list of publications.
     */
    public function index(Request $request)
    {
        $publicationQuery = Publication::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $publicationQuery->where('title', 'like', "%{$searchTerm}%")
                ->orWhere('productionYear', 'like', "%{$searchTerm}%");
        }

        $publications = $publicationQuery->orderBy('created_at', 'desc')->paginate();

        return view('rekapcetakan.index', compact('publications'));
    }
}
