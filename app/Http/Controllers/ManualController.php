<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class ManualController extends Controller
{
    public function index()
    {
        // download the manualbook by user role
        $role = Auth::user()->role->name;

        switch ($role) {
            case Role::SUPERADMIN:
                $path = 'superadmin.pdf';
                break;
            case Role::ADMINISTRATOR:
                $path = 'administrator.pdf';
                break;
            case Role::AUTHOR:
                $path = 'author.pdf';
                break;
            case Role::REVIEWER:
                $path = 'reviewer.pdf';
                break;
            default:
                $path = 'superadmin.pdf';
                break;
        }

        return response()->download(public_path('/manualbook/'.$path));
    }
}
