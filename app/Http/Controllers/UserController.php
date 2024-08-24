<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $pagination = User::orderBy('id', 'desc')->paginate(10);

        return view('user.index', compact('pagination'));
    }

    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }
}
