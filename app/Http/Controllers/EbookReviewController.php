<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\EbookReview;
use Illuminate\Http\Request;

class EbookReviewController extends Controller
{


    public function butuhReview()
    {
        $currentUser = auth()->user();

        $query = Ebook::query()->where('status', 'review')->whereIn(
            'id',
            EbookReview::where('reviewerId', $currentUser->id)->where('acc', '0')->select('ebookId')
        );
        $pagination = $query->paginate();

        return view('ebook.butuhreview.index', compact('pagination'));
    }

    public function sudahReview()
    {
        $currentUser = auth()->user();

        $query = Ebook::query()->where('status', 'review')->whereIn(
            'id',
            EbookReview::where('reviewerId', $currentUser->id)->where('acc', '!=', '0')->select('ebookId')
        );

        $pagination = $query->paginate();

        return view('ebook.butuhreview.index', compact('pagination'));
    }

    public function statusReviewView(Ebook $ebook)
    {
        return view('ebook.butuhreview.form', compact('ebook'));
    }
    public function statusReviewAction(Ebook $ebook, Request $request)
    {
        $request->validate([
            'acc' => 'required|in:1,-1'
        ]);

        $currentUser = auth()->user();
        $model = EbookReview::where('reviewerId', $currentUser->id)
            ->where('ebookId', $ebook->id)
            ->first();


        if ($model->update(['acc' => $request->acc])) {

            $adaBelumReview = $ebook->reviews()->where('acc', 0)->exists();

            if ($adaBelumReview == false) {
                $jumlahAcc =  $ebook->reviews()->where('acc', 1)->count();
                $jumlaReject =  $ebook->reviews()->where('acc', -1)->count();

                $ebook->update(['status' => $jumlahAcc >= $jumlaReject ? Ebook::STATUS_ACCEPT : Ebook::STATUS_NOT_ACCEPT]);
            }

            return redirect()->route('ebook.butuhreview')->with('success', 'Berhasil memberikan review ke ebook.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika memberikan review ke ebook')->withInputs();
    }
}
