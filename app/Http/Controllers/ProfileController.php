<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Trait\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use UploadTrait;

    public function me()
    {
        $user = Auth::user();

        return view('profile.me', compact('user'));
    }

    public function updateMe(Request $request)
    {
        $user = User::find(Auth::id());
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'nullable',
            // 'roleId' => 'required',
            'phone' => 'required',
            'bio' => 'required',
            'npwp' => 'required',
            // 'categoryId' => 'required',
            'ktp' => 'nullable|file',
            'ttd' => 'nullable|file',
            'bank' => 'required',
            'noRekening' => 'required',
            'address' => 'required',
        ]);

        if ($request->has('password') && $request->password) {
            $request->merge(['password' => bcrypt($request->password)]);
        } else {
            $request->request->remove('password');
        }

        $payload = $request->all();
        if ($request->file('ktp')) {
            $response = $this->uploadImage($request->ktp, 'ktp');
            $payload['ktp'] = $response;
        } else {
            unset($payload['ktp']);
        }
        if ($request->file('ttd')) {
            $response = $this->uploadImage($request->ttd, 'ttd');
            $payload['ttd'] = $response;
        } else {
            unset($payload['ttd']);
        }

        $user->update($payload);

        return redirect()->route('profile.me')->withSuccess('Data user berhasil diubah');
    }
}
