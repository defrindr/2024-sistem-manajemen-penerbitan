<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Trait\UploadTrait;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use UploadTrait;

    public function index()
    {
        $pagination = User::orderBy('id', 'desc')->paginate(10);

        return view('user.index', compact('pagination'));
    }

    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
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
            'bank' => 'required',
            'noRekening' => 'required',
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

        $user->update($payload);

        return redirect()->route('user.index')->withSuccess('Data user berhasil diubah');
    }
}
