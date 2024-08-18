<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\KeuanganDetail;
use App\Models\Publication;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Theme $theme)
    {

        $pagination = Keuangan::where('themeId', $theme->id)->paginate();

        return view('theme.keuangan.index', compact('pagination', 'theme'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Theme $theme)
    {

        // Get All Publications where doesnt have keuangans
        $publications = Publication::whereNotIn(
            'id',
            Keuangan::where('themeId', $theme->id)->select('publicationId')
        )
            ->where('themeId', $theme->id)
            ->get();
        return view('theme.keuangan.create', compact('theme', 'publications'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Theme  $theme
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Theme $theme)
    {
        // Validate the incoming request data
        $request->validate([
            'publicationId'   => 'required',
            'title'           => 'required',
            'year'            => 'required',
            'productionCost'  => 'required',
            'percentReviewer' => 'required',
            'sellPrice'       => 'required',
            'sellCount'       => 'required',
            'percentAdmin'    => 'required',
        ]);

        // Add the themeId to the request data
        $request->request->add([
            'themeId' => $theme->id,
        ]);

        // Check if keuangan with publicationId and year are already exists
        $keuangan = Keuangan::where('publicationId', $request->publicationId)
            ->where('year', $request->year)
            ->first();

        // If the keuangan record exists, redirect back with an error message
        if ($keuangan) {
            return redirect()->back()->withInput()->withError('Keuangan dengan tema ini sudah ada pada tahun ' . $request->year);
        }

        // Begin a database transaction
        DB::beginTransaction();
        try {
            // Create a new Keuangan record
            $payload = $request->all();
            $payload['income'] = $request->sellPrice * $request->sellCount;
            $keuangan = Keuangan::create($payload);

            // If the Keuangan record was not created, rollback the transaction and redirect back with an error message
            if (!$keuangan) {
                DB::rollBack();
                return redirect()->back()->withInput()->withError('Gagal menambahkan data keuangan');
            }

            // Calculate the percentages for admin, reviewers, and authors
            $percentAdmin = $keuangan->percentAdmin;
            $percentReviewers = $keuangan->percentReviewer;

            $percentPerReviewer = $percentReviewers;
            if ($theme->reviewer1Id && $theme->reviewer2Id) {
                $percentPerReviewer = $percentReviewers / 2;
            }

            $percentageAuthors = 100 - ($keuangan->percentAdmin + $keuangan->percentReviewer);
            if ($theme->ebooks()->count() > 0)
                $percentPerAuthor = $percentageAuthors / $theme->ebooks()->count();
            else $percentPerAuthor = $percentageAuthors;

            // Calculate the profit
            $profit = $keuangan->income - $keuangan->productionCost;

            // Create KeuanganDetail records for admin, reviewers, and authors
            KeuanganDetail::create([
                'keuanganId' => $keuangan->id,
                'userId' => null,
                'role' => 'ADMIN',
                'percent' => $percentAdmin,
                'profit' => ($profit / 100) * $percentAdmin,
            ]);

            KeuanganDetail::create([
                'keuanganId' => $keuangan->id,
                'userId' => $theme->reviewer1Id,
                'role' => 'REVIEWER',
                'percent' => $percentPerReviewer,
                'profit' => ($profit / 100) * $percentPerReviewer,
            ]);

            if ($theme->reviewer1Id && $theme->reviewer2Id) {
                KeuanganDetail::create([
                    'keuanganId' => $keuangan->id,
                    'userId' => $theme->reviewer2Id,
                    'role' => 'REVIEWER',
                    'percent' => $percentPerReviewer,
                    'profit' => ($profit / 100) * $percentPerReviewer,
                ]);
            }

            $listAuthors = $theme->ebooks()->groupBy('userId')->select('userId')->get();
            foreach ($listAuthors as $author) {
                $totalContribution = $theme->ebooks()->where('userId', $author->userId)->count();

                KeuanganDetail::create([
                    'keuanganId' => $keuangan->id,
                    'userId' => $author->userId,
                    'role' => 'AUTHOR',
                    'percent' => $percentPerAuthor * $totalContribution,
                    'profit' => ($profit / 100) * ($percentPerAuthor * $totalContribution),
                ]);
            }

            // Commit the transaction and redirect with a success message
            DB::commit();
            return redirect()->route('theme.keuangan.index', $theme)->withSuccess('Berhasil menambahkan data');
        } catch (\Throwable $th) {
            dd($th);
            // If an error occurs during the transaction, rollback and redirect back with an error message
            return redirect()->back()->withInput()->withError('Gagal menambahkan data');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Theme $theme, Keuangan $keuangan)
    {
        return view('theme.keuangan.show', compact('theme', 'keuangan'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme, Keuangan $keuangan)
    {
        $details = $keuangan->details;
        foreach ($details as $detail) $detail->delete();
        $keuangan->delete();

        return redirect()->route('theme.keuangan.index', compact('theme'))->with('success', 'Berhasil dihapus');
    }
}
