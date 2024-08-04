<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\SubTheme;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;

class SubThemeController extends Controller
{
    public function create(Theme $theme)
    {
        $reviewers = User::reviewerWithCategory($theme->categoryId)->get();

        return view('theme.sub.create', compact('theme', 'reviewers'));
    }

    public function store(Theme $theme, Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'reviewer1Id' => 'required',
            // 'reviewer2Id' => 'nullable',
        ]);

        // if ($request->reviewer1Id == $request->reviewer2Id) {
        //     return redirect()
        //         ->back()
        //         ->withInput()
        //         ->with('danger', 'Gagal menambahkan sub tema');
        // }

        $payload = $request->only('name');
        $payload['themeId'] = $theme->id;

        if (SubTheme::create($payload)) {
            return redirect()->route('theme.show', $theme)->with('success', 'Berhasil menambahkan sub tema');
        }

        return redirect()
            ->withInput()
            ->back()
            ->with('danger', 'Gagal menambahkan sub tema');
    }

    public function edit(Theme $theme, SubTheme $subTheme)
    {
        $reviewers = User::reviewerWithCategory($theme->categoryId)->get();
        return view('theme.sub.edit', compact('theme', 'subTheme', 'reviewers'));
    }

    public function update(Theme $theme, Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'reviewer1Id' => 'required',
            // 'reviewer2Id' => 'nullable',
        ]);

        // if ($request->reviewer1Id == $request->reviewer2Id) {
        //     return redirect()
        //         ->back()
        //         ->withInput()
        //         ->with('danger', 'Gagal menambahkan sub tema');
        // }


        $payload = $request->only('name');
        $payload['themeId'] = $theme->id;

        if (SubTheme::create($payload)) {
            return redirect()->route('theme.show', $theme)->with('success', 'Berhasil menambahkan sub tema');
        }

        return redirect()->back()->with('danger', 'Gagal menambahkan sub tema');
    }

    public function destroy(Theme $theme, SubTheme $subTheme)
    {
        try {
            $subTheme->delete();
            return redirect()->back()->with('success', 'Berhasil menghapus sub tema');
        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', 'Gagal menghapus sub tema');
        }
    }
}
