<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class kategoriController extends Controller
{
    public function index()
    {
        $pagination = Kategori::orderBy('id', 'desc')->get();

        return view('kategori.index', compact('pagination'));
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $payload = $request->only('name');

        if (Kategori::create($payload)) {
            return redirect()->route('kategori.index')->with('success', 'Berhasil menambahkan kategori baru.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menambahkan kategori')->withInputs();
    }

    public function edit(Kategori $category)
    {
        return view('kategori.edit', compact('category'));
    }

    public function update(Request $request, Kategori $category)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $payload = $request->only('name');

        if ($category->update($payload)) {
            return redirect()->route('kategori.index')->with('success', 'Berhasil mengubah kategori.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah kategori')->withInputs();
    }

    public function destroy(Kategori $category)
    {
        if ($category->delete()) {
            return redirect()->route('kategori.index')->with('success', 'Berhasil menghapus kategori.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menghapus kategori');
    }
}
