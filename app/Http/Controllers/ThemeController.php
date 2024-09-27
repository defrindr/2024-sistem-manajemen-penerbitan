<?php

namespace App\Http\Controllers;

use App\Exports\ThemesExport;
use App\Helpers\WordHelper;
use App\Models\Ebook;
use App\Models\EbookReview;
use App\Models\Kategori;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use App\Trait\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class ThemeController extends Controller
{
    use UploadTrait;

    public function index(Request $request)
    {
        $query = Theme::orderBy('id', 'desc');

        $currentUser = Auth::user();

        if ($currentUser->roleId == Role::findIdByName(Role::AUTHOR)) {
            $query->where('status', 'open');
        }

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $pagination = $query->paginate();

        return view('theme.index', compact('pagination'));
    }

    public function publishForm(Theme $theme)
    {
        return view('theme.publish-form', compact('theme'));
    }

    public function publishAction(Theme $theme, Request $request)
    {
        $request->validate([
            'isbn' => 'required',
            'description' => 'required',
            'file' => 'required|file',
            'cover' => 'required|file',
            'haki' => 'required|file',
        ]);

        $request->request->add(
            [
                'status' => Theme::STATUS_PUBLISH,
            ]
        );
        $payload = $request->all();
        $fileCover = $request->file('cover');
        $fileBook = $request->file('file');

        $payload['cover'] = $this->uploadImage($fileCover, Theme::PATH);
        $payload['file'] = $this->uploadImage($fileBook, Theme::PATH);
        $payload['haki'] = $this->uploadImage($fileBook, Theme::PATH);

        $success = $theme->update($payload);

        if ($success) {
            return redirect()->route('theme.index')->with('success', 'Berhasil menambahkan topik baru.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menambahkan topik')->withInputs();
    }

    public function mergeDocuments(Theme $theme)
    {
        $files = [];

        $ebooks = $theme->ebooks()->get();

        foreach ($ebooks as $ebook) {
            $files[] = storage_path('app/public/'.Ebook::FILE_PATH.'/'.$ebook->draft);
        }

        $wh = new WordHelper;
        $wh->mergeDocuments($files);
    }

    public function create()
    {
        $categories = Kategori::all();
        $reviewers = User::reviewerWithCategory(null)->get();

        return view('theme.create', compact('categories', 'reviewers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'categoryId' => 'required',
            'reviewer1Id' => 'required',
            'reviewer2Id' => 'required',
            'multipleAuthor' => 'required',
        ]);

        if (! $request->multipleAuthor) {
            $request->validate([
                'dueDate' => 'required',
            ]);
        } else {
            $request->request->add([
                'dueDate' => null,
            ]);
        }

        if ($request->reviewer1Id == $request->reviewer2Id) {
            return redirect()
                ->back()
                ->withInput()
                ->with('danger', 'Gagal menambahkan sub tema');
        }

        $payload = $request->only('name', 'description', 'price', 'categoryId', 'reviewer1Id', 'reviewer2Id', 'multipleAuthor', 'dueDate');

        if (Theme::create($payload)) {
            return redirect()->route('theme.index')->with('success', 'Berhasil menambahkan topik baru.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menambahkan topik')->withInputs();
    }

    public function edit(Theme $theme)
    {
        $categories = Kategori::all();
        $reviewers = User::reviewerWithCategory(null)->get();

        return view('theme.edit', compact('theme', 'categories', 'reviewers'));
    }

    public function update(Request $request, Theme $theme)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'categoryId' => 'required',
            'reviewer1Id' => 'required',
            'reviewer2Id' => 'required',
            'multipleAuthor' => 'required',
        ]);

        if (! $request->multipleAuthor) {
            $request->validate([
                'dueDate' => 'required',
            ]);
        } else {
            $request->request->add([
                'dueDate' => null,
            ]);
        }

        if ($request->reviewer1Id == $request->reviewer2Id) {
            return redirect()
                ->back()
                ->withInput()
                ->with('danger', 'Gagal menambahkan sub tema');
        }

        $payload = $request->only('name', 'description', 'price', 'categoryId', 'reviewer1Id', 'reviewer2Id', 'multipleAuthor', 'dueDate');

        DB::beginTransaction();
        if ($theme->update($payload)) {
            $subThemes = $theme->subThemes;
            foreach ($subThemes as $subTheme) {
                $subTheme->update(['dueDate' => $payload['dueDate']]);
            }
            DB::commit();

            return redirect()->route('theme.index')->with('success', 'Berhasil mengubah topik.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah topik topik')->withInputs();
    }

    public function destroy(Theme $theme)
    {
        if ($theme->delete()) {
            return redirect()->route('theme.index')->with('success', 'Berhasil menghapus topik.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menghapus topik');
    }

    public function show(Theme $theme)
    {
        return view('theme.show', compact('theme'));
    }

    public function review(Theme $theme)
    {
        if ($theme->status !== Theme::STATUS_OPEN) {
            return abort(403, 'Status bukan open');
        }

        DB::beginTransaction();
        $success = $theme->update(['status' => Theme::STATUS_REVIEW]);

        // foreach ($theme->ebooks as $ebook) {
        //     $success = $success && $ebook->update(['status' => Ebook::STATUS_REVIEW]);

        //     $success = $success && EbookReview::create([
        //         'ebookId' => $ebook->id,
        //         'reviewerId' => $ebook->theme->reviewer1Id,
        //         'acc' => 0,
        //     ]);

        //     if ($ebook->theme->reviewer2Id) {
        //         $success = $success && EbookReview::create([
        //             'ebookId' => $ebook->id,
        //             'reviewerId' => $ebook->theme->reviewer2Id,
        //             'acc' => 0,
        //         ]);
        //     }
        // }

        if ($success) {
            DB::commit();

            return redirect()->route('theme.index')->with('success', 'Berhasil mengubah status topik ke review.');
        }

        DB::rollBack();

        return redirect()->back()->with('danger', 'Gagal ketika mengubah status topik ke review.');
    }

    public function open(Theme $theme)
    {
        if ($theme->status !== Theme::STATUS_DRAFT) {
            return abort(403, 'Status bukan draft');
        }
        $success = $theme->update(['status' => Theme::STATUS_OPEN]);

        if ($success) {
            return redirect()->route('theme.index')->with('success', 'Berhasil mengubah status topik ke open.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah status topik ke open.');
    }

    public function close(Theme $theme)
    {
        if ($theme->status !== Theme::STATUS_OPEN) {
            return abort(403, 'Status bukan review');
        }
        $success = $theme->update(['status' => Theme::STATUS_CLOSE]);
        if ($success) {
            return redirect()->route('theme.index')->with('success', 'Berhasil mengubah status topik ke close.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika mengubah status topik ke close.');
    }

    public function downloadZip(Theme $theme)
    {
        // Variable Initialize
        $zipname = $theme->name.'.zip';
        $zippath = storage_path($theme->name.'.zip');

        if (! file_exists($zippath)) {
            $this->generateZipFile($theme, $zippath);
        }

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: '.filesize($zippath));
        readfile($zippath);
    }

    protected function generateZipFile(Theme $theme, string $zipname)
    {
        $files = [];
        // Fetch All Sub Topics
        $subTopics = $theme->subThemes;

        foreach ($subTopics as $index => $subTopic) {
            $acceptEbook = $subTopic->acceptEbook();
            // Get Only Ebook has already reviewed
            if ($acceptEbook) {
                $filenames = explode('.', $acceptEbook->draft);
                $files[] = [
                    'path' => storage_path('app/public/'.Ebook::FILE_PATH.'/'.$acceptEbook->draft),
                    'name' => $subTopic->theme->name.'/'.($index + 1).' - '.$subTopic->name.'.'.end($filenames),
                ];
            }
        }

        $zip = new ZipArchive;
        $zip->open($zipname, ZipArchive::CREATE);

        foreach ($files as $file) {
            if (file_exists($file['path'])) {
                $zip->addFile($file['path'], $file['name']);
            }
        }

        $zip->close();
    }

    public function haki(Theme $theme)
    {
        $user = Auth::user();

        return view('ebook.haki', compact('ebook'));
    }

    public function hakiStore(Request $request, Theme $theme)
    {
        $user = Auth::user();
        $request->validate([
            'haki' => 'required',
        ]);

        $payload = $request->only('haki');

        if ($theme->update($payload)) {
            return redirect()->route('ebook.me')->with('success', 'Berhasil menyetujui haki ebook.');
        }

        return redirect()->back()->with('danger', 'Gagal ketika menyetujui haki ebook')->withInputs();
    }

    public function export()
    {
        return Excel::download(new ThemesExport, 'themes.xlsx');
    }
}
