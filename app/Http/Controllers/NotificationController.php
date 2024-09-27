<?php

namespace App\Http\Controllers;

use App\Models\EbookReview;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderBy('created_at', 'DESC')->paginate(10);

        return view('notification.index', compact('notifications'));
    }

    public function read(Notification $notification)
    {
        $notification->update(['isRead' => true]);

        $reviewer = EbookReview::where('ebookId', $notification->ebook->id)
            ->where('reviewerId', $notification->userId)->first();

        if ($reviewer) {
            session()->flash('danger', $reviewer->reviewer->name.' harus mereview pengajuan. Silahkan hubungi kontak berikut : '.$reviewer->reviewer->email.', '.$reviewer->reviewer->phone);
        }

        return redirect()->route('theme.show', [
            'theme' => $notification->ebook->theme->id,
            'highlight' => $notification->ebook->id,
            '_' => $notification->userId,
        ]);
    }
}
