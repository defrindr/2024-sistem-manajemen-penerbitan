<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Theme;
use App\Trait\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    use UploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Theme $theme)
    {

        $pagination = Publication::where('themeId', $theme->id)->paginate();

        return view('theme.publication.index', compact('pagination', 'theme'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Theme $theme)
    {
        return view('theme.publication.create', compact('theme'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Theme $theme)
    {
        $request->validate([
            'title' => 'required',
            'numberOfPrinting' => 'required',
            'cover' => 'nullable|file',
            'productionYear' => 'required',
            'totalProduction' => 'required',
            'price' => 'required',
        ]);

        $request->request->add([
            'themeId' => $theme->id,
        ]);


        DB::beginTransaction();
        try {
            $payload = $request->all();
            $fileCover = $request->file('cover');
            if ($fileCover) {
                $payload['cover'] = $this->uploadImage($fileCover, Theme::PATH);
            } else {
                $payload['cover'] = $theme->cover;
            }
            Publication::create($payload);
            DB::commit();
            return redirect()->route('theme.publication.index', $theme)->withSuccess('Berhasil menambahkan data');
        } catch (\Throwable $th) {
            return redirect()->back()->withError('Gagal menambahkan data');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Theme $theme, Publication $publication)
    {
        return view('theme.publication.show', compact('theme', 'publication'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme, Publication $publication)
    {
        $keuangans = $publication->keuangans;
        foreach ($keuangans as $keuangan) {
            $details = $keuangan->details;
            foreach ($details as $detail) {
                $detail->delete();
            }
            $keuangan->delete();
        }
        $publication->delete();

        return redirect()->route('theme.publication.index', compact('theme'))->with('success', 'Berhasil dihapus');
    }
}
