<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\EbookReview;
use App\Models\Kategori;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ThemeController extends Controller
{
    public function index(Request $request)
    {
        $query = Theme::orderBy('id', 'desc');

        $currentUser = auth()->user();

        if ($currentUser->roleId == Role::findIdByName(Role::AUTHOR)) {
            $query->where('status', 'open');
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $pagination = $query->paginate();

        return view('theme.index', compact('pagination'));
    }

    public function publishForm(Theme $theme)
    {
        return view('theme.publish-form', compact('theme'));
    }

    public function publishAction(Theme $theme, Request $request)
    {
        $request->validate([
            'isbn' => 'required'
        ]);


        $success = $theme->update(['isbn' => $request->isbn, 'status' => Theme::STATUS_PUBLISH]);

        if ($success) {
            return redirect()->route('theme.index')->with('success', 'Berhasil menambahkan topik baru.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menambahkan topik')->withInputs();
    }

    public function create()
    {
        $categories = Kategori::all();
        return view('theme.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'dueDate'     => 'required',
            'price'       => 'required',
            'description' => 'required',
            'categoryId' => 'required',
        ]);

        $payload = $request->only('name', 'dueDate', 'description', 'price', 'categoryId');

        if (Theme::create($payload)) {
            return redirect()->route('theme.index')->with('success', 'Berhasil menambahkan topik baru.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menambahkan topik')->withInputs();
    }

    public function edit(Theme $theme)
    {
        $categories = Kategori::all();
        return view('theme.edit', compact('theme', 'categories'));
    }

    public function update(Request $request, Theme $theme)
    {
        $request->validate([
            'name' => 'required',
            'dueDate' => 'required',
            'price' => 'required',
            'description' => 'required',
            'categoryId' => 'required',
        ]);

        $payload = $request->only('name', 'dueDate', 'description', 'price', 'categoryId');

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
        if ($theme->status !== Theme::STATUS_OPEN) {
            return abort(403, 'Status bukan open');
        }

        DB::beginTransaction();
        $success = $theme->update(['status' => Theme::STATUS_REVIEW]);

        foreach ($theme->ebooks as $ebook) {
            $success = $success && $ebook->update(['status' => Ebook::STATUS_REVIEW]);

            $success = $success && EbookReview::create([
                'ebookId' => $ebook->id,
                'reviewerId' => $ebook->subTheme->reviewer1Id,
                'acc' => 0,
            ]);

            if ($ebook->subTheme->reviewer2Id) {
                $success = $success && EbookReview::create([
                    'ebookId' => $ebook->id,
                    'reviewerId' => $ebook->subTheme->reviewer2Id,
                    'acc' => 0,
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
        if ($theme->status !== Theme::STATUS_DRAFT) {
            return abort(403, 'Status bukan draft');
        }
        $success = $theme->update(['status' => Theme::STATUS_OPEN]);

        if ($success) {
            return redirect()->route('theme.index')->with('success', 'Berhasil mengubah status topik ke open.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah status topik ke open.');
    }

    public function close(Theme $theme)
    {
        if ($theme->status !== Theme::STATUS_REVIEW) {
            return abort(403, 'Status bukan review');
        }
        $success = $theme->update(['status' => Theme::STATUS_CLOSE]);
        if ($success) {
            return redirect()->route('theme.index')->with('success', 'Berhasil mengubah status topik ke close.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah status topik ke close.');
    }
}
