<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\EbookReview;
use App\Models\Role;
use App\Models\Theme;
use App\Trait\UploadTrait;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    use UploadTrait;

    public function me()
    {
        $currentUser = auth()->user();

        $query = Ebook::query();
        if ($currentUser->roleId !== Role::findIdByName(Role::ADMINISTRATOR)) {
            $query->where('userId', $currentUser->id);
        }

        $pagination = $query->paginate();

        return view('ebook.me', compact('pagination'));
    }


    public function siapPublish()
    {
        $currentUser = auth()->user();

        $query = Ebook::query()->where('status', Ebook::STATUS_ACCEPT);
        $pagination = $query->paginate();

        return view('ebook.siappublish.index', compact('pagination'));
    }

    public function aturRoyalti(Ebook $ebook)
    {
        return view('ebook.atur-royalti', compact('ebook'));
    }

    public function create(Theme $theme)
    {
        return view('ebook.create', compact('theme'));
    }

    public function aturRoyaltiStore(Request $request, Ebook $ebook)
    {
        $request->validate([
            'royalty' => 'required',
        ]);

        $payload = $request->only('royalty');

        if ($ebook->update($payload)) {
            return redirect()->route('ebook.me')->with('success', 'Berhasil mengatur royalti.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengatur royalti')->withInputs();
    }

    public function store(Request $request, Theme $theme)
    {
        $request->validate([
            'title' => 'required',
            'draft' => 'required|file',
        ], [
            'required' => ':attribute tidak boleh kosong',
            'file' => ':attribute harus berupa file.',
        ]);

        $payload = $request->only('title');
        $payload['themeId'] = $theme->id;
        $payload['userId'] = auth()->id();
        $payload['status'] = Ebook::STATUS_SUBMIT;

        $payload['draft'] = $this->uploadImage($request->file('draft'), Ebook::FILE_PATH);

        if (Ebook::create($payload)) {
            return redirect()->route('ebook.me')->with('success', 'Berhasil menambahkan ebook.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menambahkan ebook')->withInputs();
    }

    public function edit(Ebook $ebook)
    {
        return view('ebook.edit', compact('ebook'));
    }

    public function update(Ebook $ebook, Request $request)
    {
        $request->validate([
            'title' => 'required',
            'draft' => 'file',
        ], [
            'required' => ':attribute tidak boleh kosong',
            'file' => ':attribute harus berupa file.',
        ]);

        $payload = $request->only('title');
        if ($request->has('draft')) {
            $payload['draft'] = $this->uploadImage($request->file('draft'), Ebook::FILE_PATH, $ebook->draft);
        } else {
            $payload['draft'] = $ebook->draft;
        }

        if ($ebook->update($payload)) {
            return redirect()->route('ebook.me')->with('success', 'Berhasil mengubah ebook.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah ebook')->withInputs();
    }


    public function publish(Ebook $ebook)
    {
        if ($ebook->status !== Ebook::STATUS_ACCEPT) return abort(403, 'Status bukan Accept');
        $success = $ebook->update(['status' => Ebook::STATUS_PUBLISH]);
        if ($success) {
            return redirect()->route('ebook.siap-publish')->with('success', 'Berhasil mengubah status ebook ke publish.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah status ebook ke publish.');
    }
}
