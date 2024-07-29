<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThemeController extends Controller
{
    public function index()
    {
        $pagination = Theme::orderBy('id', 'desc')->get();

        return view('theme.index', compact('pagination'));
    }

    public function create()
    {
        return view('theme.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'dueDate' => 'required',
            'description' => 'required',
        ], [
            'name.required' => 'Nama tidak boleh kosong',
            'dueDate.required' => 'Deadline tidak boleh kosong',
            'description.required' => 'Deskripsi tidak boleh kosong',
        ]);

        $payload = $request->only('name', 'dueDate', 'description');

        if (Theme::create($payload)) {
            return redirect()->route('theme.index')->with('success', 'Berhasil menambahkan topik baru.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menambahkan topik')->withInputs();
    }

    public function edit(Theme $theme)
    {
        return view('theme.edit', compact('theme'));
    }

    public function update(Request $request, Theme $theme)
    {
        $request->validate([
            'name' => 'required',
            'dueDate' => 'required',
            'description' => 'required',
        ], [
            'name.required' => 'Nama tidak boleh kosong',
            'dueDate.required' => 'Deadline tidak boleh kosong',
            'description.required' => 'Deskripsi tidak boleh kosong',
        ]);

        $payload = $request->only('name', 'dueDate', 'description');

        if ($theme->update($payload)) {
            return redirect()->route('theme.index')->with('success', 'Berhasil mengubah topik.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah topik topik')->withInputs();
    }

    public function destroy(Theme $theme)
    {
        if ($theme->delete()) {
            return redirect()->route('theme.index')->with('success', 'Berhasil menghapus topik.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menghapus topik');
    }

    public function show(Theme $theme)
    {
        return view('theme.show', compact('theme'));
    }

    public function review(Theme $theme)
    {
        if ($theme->status !== Theme::STATUS_OPEN) return abort(403, 'Status bukan open');

        DB::beginTransaction();
        $success = $theme->update(['status' => Theme::STATUS_REVIEW]);

        $reviewers = User::where('roleId', Role::findIdByName(Role::REVIEWER))->get();

        foreach ($theme->ebooks as $ebook) {
            $success = $success && $ebook->update(['status' => Ebook::STATUS_REVIEW]);
            
            foreach ($reviewers as $review) {
                    $success = $success && EbookReview::create([
                        'ebookId' => $ebook->id,
                        'reviewerId' => $review->id,
                        'acc' => 0
                    ]);
            }
        }

        if ($success) {
            DB::commit();
            return redirect()->route('theme.index')->with('success', 'Berhasil mengubah status topik ke review.');
        }

        DB::rollBack();
        return redirect()->back()->with('danger', 'Gagal ketika mengubah status topik ke review.');
    }


    public function open(Theme $theme)
    {
        if ($theme->status !== Theme::STATUS_DRAFT) return abort(403, 'Status bukan draft');
        $success = $theme->update(['status' => Theme::STATUS_OPEN]);

        if ($success) {
            return redirect()->route('theme.index')->with('success', 'Berhasil mengubah status topik ke open.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah status topik ke open.');
    }

    public function close(Theme $theme)
    {
        if ($theme->status !== Theme::STATUS_OPEN) return abort(403, 'Status bukan open');
        $success = $theme->update(['status' => Theme::STATUS_REVIEW]);
        if ($success) {
            return redirect()->route('theme.index')->with('success', 'Berhasil mengubah status topik ke close.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah status topik ke close.');
    }
}
