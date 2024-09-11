<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function indexAction(Request $request)
    {
        // Retrieve the feedback data from the request
        $roleId = Auth::user()->role->name;
        if (! in_array(
            $roleId,
            [Role::ADMINISTRATOR, Role::SUPERADMIN]
        )) {
            return $this->FormAction();
        }

        $pagination = Feedback::get();

        return view('feedback.index', compact('pagination'));
    }

    public function FormAction()
    {
        return view('feedback.form');
    }

    public function storeAction(Request $request)
    {
        // Validate the request
        $request->validate([
            'content' => ' required',
        ]);

        // Store the feedback into the database
        $payload = $request->all();
        $payload['userId'] = Auth::id();
        Feedback::create($payload);

        // Redirect back to the feedback form with a success message
        return redirect()->route('feedback.form')->with('success', 'Feedback berhasil disimpan');
    }
}
