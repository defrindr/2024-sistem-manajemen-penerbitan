<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\EbookReview;
use App\Models\Role;
use App\Models\SubTheme;
use App\Models\Theme;
use App\Models\User;
use App\Trait\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EbookController extends Controller
{
    use UploadTrait;

    public function me()
    {
        $currentUser = Auth::user();

        $query = Ebook::query();
        if ($currentUser->roleId !== Role::findIdByName(Role::ADMINISTRATOR)) {
            $query->where('userId', $currentUser->id);
        }

        $pagination = $query->paginate();

        return view('ebook.me', compact('pagination'));
    }

    public function progress(Ebook $ebook)
    {
        $currentUser = Auth::user();

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

        if ($ebook->theme->multipleAuthor) {
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
        } else {
            $subThemes = $ebook
                ->theme
                ->subThemes()
                ->where(
                    'id',
                    '!=',
                    $ebook->subthemeId
                )
                ->get();

            $ebooks = [$ebook];
            foreach ($subThemes as $subTheme) {
                $ebooks[] = Ebook::create([
                    'themeId' => $ebook->theme->id,
                    'subthemeId' => $subTheme->id,
                    'userId' => $ebook->userId,
                    'title' => $subTheme->theme->name.' - '.$subTheme->name,
                    'draft' => null,
                    'proofOfPayment' => $ebook->proofOfPayment,
                    'royalty' => $ebook->royalty,
                    'status' => Ebook::STATUS_SUBMIT,
                ]);
            }

            foreach ($ebooks as $eb) {
                $success = $success && EbookReview::create([
                    'ebookId' => $eb->id,
                    'reviewerId' => $eb->theme->reviewer1Id,
                    'acc' => 0,
                ]);

                if ($eb->theme->reviewer2Id) {
                    $success = $success && EbookReview::create([
                        'ebookId' => $eb->id,
                        'reviewerId' => $eb->theme->reviewer2Id,
                        'acc' => 0,
                    ]);
                }
            }
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
        $currentUser = Auth::user();

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
        $user = User::where('id', Auth::id())->first();

        if (! $user->ktp) {
            return redirect()->route('profile.me')->with('danger', 'KTP belum diatur');
        }
        if (! $user->ttd) {
            return redirect()->route('profile.me')->with('danger', 'TTD belum diatur');
        }

        // Create ebook when access this page, if not exist
        $ebook = $theme->ebooks()->where('subthemeId', $subTheme->id)->first();
        if (! $ebook) {
            $ebook = $theme->ebooks()->create([
                'subthemeId' => $subTheme->id,
                'userId' => Auth::user()->id,
                'title' => $subTheme->theme->name.' - '.$subTheme->name,
                'draft' => null,
                'proofOfPayment' => null,
                'royalty' => 0,
                'status' => Ebook::STATUS_PAYMENT,
                // Due date, 24 hours from now
                'dueDate' => now()->addHours(24),
            ]);
        }

        return view('ebook.create', compact('theme', 'subTheme', 'ebook'));
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

        $user = User::where('id', Auth::id())->first();

        if (! $user->ktp) {
            return redirect()->route('profile.me')->with('danger', 'KTP belum diatur');
        }
        if (! $user->ttd) {
            return redirect()->route('profile.me')->with('danger', 'TTD belum diatur');
        }

        $payload = $request->only('title');
        $payload['themeId'] = $theme->id;
        $payload['subthemeId'] = $subTheme->id;
        $payload['userId'] = $user->id;
        $payload['status'] = Ebook::STATUS_PENDING;
        $payload['ttd'] = $user->ttd;
        $payload['ktp'] = $user->ktp;

        $payload['proofOfPayment'] = $this->uploadImage($request->file('proofOfPayment'), Ebook::FILE_PATH);

        // Find existing ebook from this subtheme and user
        $ebook = $theme->ebooks()->where('subthemeId', $subTheme->id)->where('userId', auth()->id())->first();

        if ($ebook->update($payload)) {
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
