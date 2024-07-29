<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function view()
    {
        $roles = Role::where('name', '!=', Role::SUPERADMIN)->get();

        return view('auth.register', compact('roles'));
    }

    public function action(Request $request)
    {
        $rules = [
            'email' => 'required|unique:users,email',
            'name' => 'required|min:3',
            'password' => 'required|min:6',
            'roleId' => 'required',
        ];

        $request->validate($rules);

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
