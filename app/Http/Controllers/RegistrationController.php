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
            'phone' => 'required',
            'npwp' => 'required',
        ];

        $request->validate($rules);
        $payload = $request->only(
            'email',
            'name',
            'password',
            'roleId',
            'phone',
            'npwp',
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
