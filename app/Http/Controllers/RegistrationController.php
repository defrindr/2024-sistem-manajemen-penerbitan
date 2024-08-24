<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Role;
use App\Models\User;
use App\Trait\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    use UploadTrait;

    public function view()
    {
        $roles = Role::where('name', '!=', Role::SUPERADMIN)->get();
        $categories = Kategori::all();

        return view('auth.register', compact('roles', 'categories'));
    }

    public function action(Request $request)
    {
        $rules = [
            'email' => 'required|unique:users,email',
            'name' => 'required|min:3',
            'password' => 'required|min:6',
            'roleId' => 'required',
            'phone' => 'required',
            'npwp' => 'required',
            'ktp' => 'required|file',
            'bank' => 'required',
            'noRekening' => 'required',
        ];

        if ($request->roleId == Role::findIdByName(Role::REVIEWER)) {
            $rules['categoryId'] = 'required';
        }

        $request->validate($rules);

        $ktp = $this->uploadImage($request->file('ktp'), 'ktp');
        if (! $ktp) {
            abort(400, 'Image upload failed');
        }

        $request->request->add(['ktp' => $ktp]);
        if ($user = User::create($request->all())) {
            Auth::login($user, true);

            return redirect()->route('dashboard');
        }

        return redirect()
            ->back()
            ->with('danger', 'Gagal melakukan pendaftaran.')
            ->withInput();
    }
}
