<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\EbookReview;
use App\Trait\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EbookReviewController extends Controller
{
    use UploadTrait;

    public function butuhReview()
    {
        $currentUser = Auth::user();

        $query = Ebook::query()
            // ->where('status', 'review')
            ->whereIn(
                'id',
                EbookReview::where('reviewerId', $currentUser->id)
                    ->where('acc', '0')
                    ->select('ebookId')
            );
        $pagination = $query->paginate();

        return view('ebook.butuhreview.index', compact('pagination'));
    }

    public function sudahReview()
    {
        $currentUser = Auth::user();

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
            'acc' => 'required|in:1,-1',
            'comment' => 'required',
            'draft' => 'file|nullable'
        ]);

        DB::beginTransaction();
        $currentUser = Auth::user();
        $model = EbookReview::where('reviewerId', $currentUser->id)
            ->where('ebookId', $ebook->id)
            ->first();

        if ($model->update($request->only('acc', 'comment'))) {
            $adaBelumReview = $ebook->reviews()->where('acc', 0)->exists();

            if ($adaBelumReview == false) {
                $jumlaReject = $ebook->reviews()->where('acc', -1)->count();

                $ebook->update(['status' => $jumlaReject == 0 ? Ebook::STATUS_ACCEPT : Ebook::STATUS_NOT_ACCEPT]);
            }

            if ($request->has('draft')) {
                $ebook->draft = $this->uploadImage($request->file('draft'), Ebook::FILE_PATH, $ebook->draft);
            } else {
                $ebook->draft = $ebook->draft;
            }

            $ebook->save();

            DB::commit();
            return redirect()->route('ebook.butuhreview')->with('success', 'Berhasil memberikan review ke ebook.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika memberikan review ke ebook')->withInputs();
    }
}
