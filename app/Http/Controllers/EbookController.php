<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\EbookReview;
use App\Models\Role;
use App\Models\SubTheme;
use App\Models\Theme;
use App\Trait\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EbookController extends Controller
{
    use UploadTrait;

    public function me()
    {
        $currentUser = auth()->user();

        $query = Ebook::query();
        if ($currentUser->roleId !== Role::findIdByName(Role::ADMINISTRATOR)) {
            $query->where('userId', $currentUser->id);
        }

        $pagination = $query->paginate();

        return view('ebook.me', compact('pagination'));
    }

    public function progress(Ebook $ebook)
    {
        $currentUser = auth()->user();

        $theme = $ebook->theme;

        return view('ebook.progress', compact('theme'));
    }

    public function konfirmasiPembayaranList()
    {
        $query = Ebook::query()->where('status', 'pending');
        $pagination = $query->paginate();
        return view('ebook.konfirmasi-pembayaran-list', compact('pagination'));
    }

    public function konfirmasiPembayaranAction(Ebook $ebook)
    {
        $success = $ebook->update(['status' => Ebook::STATUS_SUBMIT]);

        $success = $success && EbookReview::create([
            'ebookId' => $ebook->id,
            'reviewerId' => $ebook->theme->reviewer1Id,
            'acc' => 0,
        ]);

        if ($ebook->theme->reviewer2Id) {
            $success = $success && EbookReview::create([
                'ebookId' => $ebook->id,
                'reviewerId' => $ebook->theme->reviewer2Id,
                'acc' => 0,
            ]);
        }

        if ($success) {
            return redirect()->route('ebook.konfirmasi-pembayaran-list')->with('success', 'Berhasil mengonfirmasi pembayaran.');
        }

        return redirect()->back()->with('danger', 'Gagal mengonfirmasi pembayaran')->withInputs();
    }

    public function konfirmasiPengajuanAction(Ebook $ebook)
    {
        $success = $ebook->update(['status' => Ebook::STATUS_REVIEW]);

        if ($success) {
            return redirect()->route('ebook.me')->with('success', 'Berhasil mengajukan karya.');
        }

        return redirect()->back()->with('danger', 'Gagal mengajukan karya')->withInputs();
    }

    public function siapPublish()
    {
        $currentUser = auth()->user();

        $query = Ebook::query()->where('status', Ebook::STATUS_ACCEPT);
        $pagination = $query->paginate();

        return view('ebook.siappublish.index', compact('pagination'));
    }

    public function aturRoyalti(Ebook $ebook)
    {
        return view('ebook.atur-royalti', compact('ebook'));
    }

    public function create(Theme $theme, SubTheme $subTheme)
    {
        return view('ebook.create', compact('theme', 'subTheme'));
    }

    public function aturRoyaltiStore(Request $request, Ebook $ebook)
    {
        $request->validate([
            'royalty' => 'required',
        ]);

        $payload = $request->only('royalty');

        if ($ebook->update($payload)) {
            return redirect()->route('ebook.me')->with('success', 'Berhasil mengatur royalti.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengatur royalti')->withInputs();
    }

    public function store(Request $request, Theme $theme, SubTheme $subTheme)
    {
        $request->validate([
            'title' => 'required',
            'proofOfPayment' => 'required|file',
        ], [
            'required' => ':attribute tidak boleh kosong',
            'proofOfPayment.required' => 'Bukti Pembayaran tidak boleh kosong',
            'proofOfPayment.file' => 'Bukti Pembayaran harus berupa file.',
        ]);

        $payload = $request->only('title');
        $payload['themeId'] = $theme->id;
        $payload['subthemeId'] = $subTheme->id;
        $payload['userId'] = auth()->id();
        $payload['status'] = Ebook::STATUS_PENDING;

        $payload['proofOfPayment'] = $this->uploadImage($request->file('proofOfPayment'), Ebook::FILE_PATH);

        if (Ebook::create($payload)) {
            return redirect()->route('ebook.me')->with('success', 'Berhasil menambahkan ebook.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menambahkan ebook')->withInputs();
    }

    public function edit(Ebook $ebook)
    {
        return view('ebook.edit', compact('ebook'));
    }

    public function update(Ebook $ebook, Request $request)
    {
        $request->validate([
            'title' => 'required',
            'draft' => 'file',
        ], [
            'required' => ':attribute tidak boleh kosong',
            'file' => ':attribute harus berupa file.',
        ]);

        $payload = $request->only('title');
        if ($request->has('draft')) {
            $payload['draft'] = $this->uploadImage($request->file('draft'), Ebook::FILE_PATH, $ebook->draft);
        } else {
            $payload['draft'] = $ebook->draft;
        }
        $payload['status'] = Ebook::STATUS_REVIEW;

        try {
            DB::beginTransaction();
            $reviews = $ebook->reviews()->where('acc', -1)->get();

            if (count($reviews) == 0) {
                $success = true;
            } else {
                $success = $ebook->reviews()->where('acc', -1)->update(['acc' => 0]);
            }

            if ($ebook->update($payload) && $success) {
                DB::commit();
                return redirect()->route('ebook.me')->with('success', 'Berhasil mengubah ebook.');
            }
            DB::rollBack();

            return redirect()->back()->with('danger', 'Gagal ketika mengubah ebook');
        } catch (\Throwable $th) {
            return redirect()->back()->with('danger', 'Gagal');
        }
    }

    public function publish(Ebook $ebook)
    {
        if ($ebook->status !== Ebook::STATUS_ACCEPT) {
            return abort(403, 'Status bukan Accept');
        }
        $success = $ebook->update(['status' => Ebook::STATUS_PUBLISH]);
        if ($success) {
            return redirect()->route('ebook.siap-publish')->with('success', 'Berhasil mengubah status ebook ke publish.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah status ebook ke publish.');
    }
}
