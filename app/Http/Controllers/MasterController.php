<?php

namespace App\Http\Controllers;

use App\Models\Master;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller
{
    public function index()
    {
        // if author show template
        $currentUser = Auth::user();
        if (
            $currentUser->roleId == Role::findIdByName(Role::ADMINISTRATOR)
            || $currentUser->roleId == Role::findIdByName(Role::SUPERADMIN)
        ) {
            // if admin show all template
            $template = Master::first();

            return view('master.index', compact('template'));
        }

        return $this->download();
    }

    public function download()
    {
        $template = Master::first();

        if (! $template) {
            return abort(404, 'No template found');
        }

        return response()->download(storage_path('/app/' . $template->templateWord));
    }

    public function store()
    {
        // if admin update template
        $currentUser = Auth::user();
        if (
            $currentUser->roleId == Role::findIdByName(Role::ADMINISTRATOR)
            || $currentUser->roleId == Role::findIdByName(Role::SUPERADMIN)
        ) {
            $request = request();
            $template = Master::first();

            if ($template) {
                $template->templateWord = $request->file('templateWord')->store('public/template');
                $template->save();

                return redirect()->route('template-penulisan.index')->with('success', 'Berhasil mengubah template.');
            } else {
                $template = new Master();
                $template->templateWord = $request->file('templateWord')->store('public/template');
                $template->save();

                return redirect()->route('template-penulisan.index')->with('success', 'Berhasil membuat template.');
            }
        }

        return redirect()->route('template-penulisan.index')->with('danger', 'Gagal mengubah template.');
    }
}
