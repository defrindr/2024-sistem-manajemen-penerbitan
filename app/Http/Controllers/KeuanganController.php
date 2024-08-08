<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\KeuanganDetail;
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
        return view('theme.keuangan.create', compact('theme'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Theme $theme)
    {
        $request->validate([
            'title'           => 'required',
            'productionCost'  => 'required',
            'income'          => 'required',
            'percentReviewer' => 'required',
            'percentAdmin'    => 'required',
        ]);

        $request->request->add([
            'themeId' => $theme->id,
        ]);


        DB::beginTransaction();
        try {
            $keuangan = Keuangan::create($request->all());
            if (!$keuangan) {
                DB::rollBack();
                return redirect()->back()->withInput()->withError('Gagal menambahkan data keuangan');
            }

            $percentAdmin = $keuangan->percentAdmin;
            $percentReviewers = $keuangan->percentReviewer;

            $percentPerReviewer = $percentReviewers;
            if ($theme->reviewer1Id && $theme->reviewer2Id) {
                $percentPerReviewer = $percentReviewers / 2;
            }

            $percentageAuthors = 100 - ($keuangan->percentAdmin + $keuangan->percentReviewer);
            $percentPerAuthor = $percentageAuthors / $theme->ebooks()->count();

            // Tambahkan detail data admin
            $profit = $keuangan->income - $keuangan->productionCost;

            KeuanganDetail::create([
                'keuanganId' => $keuangan->id,
                'userId' => null,
                'role' => 'ADMIN',
                'profit' => ($profit / 100) * $percentAdmin,
            ]);


            KeuanganDetail::create([
                'keuanganId' => $keuangan->id,
                'userId' => $theme->reviewer1Id,
                'role' => 'REVIEWER',
                'profit' => ($profit / 100) * $percentPerReviewer,
            ]);
            if ($theme->reviewer1Id && $theme->reviewer2Id) {
                KeuanganDetail::create([
                    'keuanganId' => $keuangan->id,
                    'userId' => $theme->reviewer2Id,
                    'role' => 'REVIEWER',
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
                    'profit' => ($profit / 100) * ($percentPerAuthor * $totalContribution),
                ]);
            }

            DB::commit();
            return redirect()->route('theme.keuangan.index', $theme)->withSuccess('Berhasil menambahkan data');
        } catch (\Throwable $th) {
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
