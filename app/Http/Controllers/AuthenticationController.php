<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function logonView()
    {
        return view('auth.login');
    }

    public function logonAction(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('dashboard');
        }

        return redirect()
            ->back()
            ->with('danger', 'Kredensial tidak dapat ditemukan.')
            ->withInput();
    }

    public function logoutAction()
    {
        if (! Auth::user()) {
            return redirect()->route('login');
        }

        Auth::logout();

        return redirect()->route('login');
    }
}
