<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
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
        ];


        if ($request->roleId == Role::findIdByName(Role::REVIEWER)) {
            $rules['categoryId'] = 'required';
        }

        $request->validate($rules);
        $payload = $request->only(
            'email',
            'name',
            'password',
            'roleId',
            'phone',
            'npwp',
            'categoryId',
        );

        if ($user = User::create($payload)) {
            Auth::login($user, true);

            return redirect()->route('dashboard');
        }

        return redirect()
            ->back()
            ->with('danger', 'Gagal melakukan pendaftaran.')
            ->withInput();
    }
}
