<?php

use App\Helpers\CheckDeadline;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\EbookReviewController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\ManualController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RekapitulasiController;
use App\Http\Controllers\RekapitulasiDetailController;
use App\Http\Controllers\SubThemeController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UserController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    // check if from cli
    if (php_sapi_name() !== 'cli') {
        CheckDeadline::run();
    }

    $admin = Role::ADMINISTRATOR;
    $sa = Role::SUPERADMIN;
    $author = Role::AUTHOR;
    $reviewer = Role::REVIEWER;

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('category', KategoriController::class)->except(['show'])->names('kategori');
    Route::resource('user', UserController::class)->only(['index', 'show', 'edit', 'update']);
    Route::get('/topik/unduh', [ThemeController::class, 'export'])->name('theme.export');
    Route::resource('topik', ThemeController::class)
        ->names('theme')
        ->parameters(['topik' => 'theme']);

    Route::resource('topik.keuangan', KeuanganController::class)
        ->names('theme.keuangan')
        ->parameters(['topik' => 'theme'])
        ->except(['update', 'edit']);

    Route::resource('topik.publication', PublicationController::class)
        ->names('theme.publication')
        ->parameters(['topik' => 'theme'])->except(['update', 'edit']);
    Route::resource('topik.sub-topik', SubThemeController::class)
        ->names('themes.subThemes')
        ->parameters(['sub-topik' => 'subTheme', 'topik' => 'theme'])
        ->except(['index', 'show']);
    Route::group(['prefix' => 'topik'], function () {

        Route::get('/theme/{theme}/unduh', [SubThemeController::class, 'export'])->name('themes.sub-theme.export');
        Route::get('/theme/{theme}/publish-form', [ThemeController::class, 'publishForm'])
            ->name('theme.publish-form');
        Route::post('/theme/{theme}/publish-action', [ThemeController::class, 'publishAction'])
            ->name('theme.publish-action');

        Route::post('{theme}/close', [ThemeController::class, 'close'])
            ->name('theme.close');

        Route::post('{theme}/open', [ThemeController::class, 'open'])
            ->name('theme.open');

        Route::post('{theme}/review', [ThemeController::class, 'review'])
            ->name('theme.review');

        Route::get('{theme}/download-zip', [ThemeController::class, 'downloadZip'])
            ->name('theme.download-zip');

        Route::get('{theme}/merge-documents', [ThemeController::class, 'mergeDocuments'])
            ->name('theme.merge-documents');
    });

    Route::group(['prefix' => 'keuangan'], function () use ($sa, $admin) {
        Route::get('/{theme}/{keuangan}/{detail}/upload-bukti', [KeuanganController::class, 'formUploadBukti'])
            ->name('theme.keuangan-detail.bukti')
            ->middleware('rbac:'.implode(',', [$sa, $admin]));
        Route::post('/{theme}/{keuangan}/{detail}/upload-bukti', [KeuanganController::class, 'storeBukti'])
            ->name('theme.keuangan-detail.bukti-store')
            ->middleware('rbac:'.implode(',', [$sa, $admin]));
    });

    Route::group(['prefix' => 'ebook'], function () use ($sa, $author, $reviewer, $admin) {
        Route::get('/butuh-konfirmasi-pembayaran/list', [EbookController::class, 'konfirmasiPembayaranList'])
            ->name('ebook.konfirmasi-pembayaran-list')->middleware('rbac:'.implode(',', [$sa, $admin]));

        Route::post('/butuh-konfirmasi-pembayaran/{ebook}/confirm', [EbookController::class, 'konfirmasiPembayaranAction'])
            ->name('ebook.konfirmasi-pembayaran-action')->middleware('rbac:'.implode(',', [$sa, $admin]));

        Route::post('/konfirmasi-pengajuan/{ebook}/confirm', [EbookController::class, 'konfirmasiPengajuanAction'])
            ->name('ebook.konfirmasi-ajukan-action')->middleware('rbac:'.implode(',', [$sa, $author]));

        Route::get('/{theme}/{subTheme}/create', [EbookController::class, 'create'])
            ->name('ebook.create')->middleware('rbac:'.implode(',', [$sa, $author]));

        Route::post('/{theme}/{subTheme}/store', [EbookController::class, 'store'])
            ->name('ebook.store')->middleware('rbac:'.implode(',', [$sa, $author]));

        Route::get('{ebook}/atur-royalti', [EbookController::class, 'aturRoyalti'])
            ->name('ebook.atur-royalti')->middleware('rbac:'.implode(',', [$sa, $author]));

        Route::post('{ebook}/atur-royalti', [EbookController::class, 'aturRoyaltiStore'])
            ->name('ebook.atur-royalti.store')->middleware('rbac:'.implode(',', [$sa, $author]));

        Route::get('me', [EbookController::class, 'me'])
            ->name('ebook.me')->middleware('rbac:'.implode(',', [$sa, $author]));

        Route::get('progress/{ebook}', [EbookController::class, 'progress'])
            ->name('ebook.progress')->middleware('rbac:'.implode(',', [$sa, $author]));

        // Route::get('{ebook}/haki', [EbookController::class, 'haki'])
        //     ->name('ebook.haki')->middleware('rbac:' . implode(',', [$sa, $author]));

        // Route::put('{ebook}/haki', [EbookController::class, 'hakiStore'])
        //     ->name('ebook.haki.store')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::group(['prefix' => 'siap-publish'], function () use ($sa, $admin) {
            Route::get('/', [EbookController::class, 'siapPublish'])
                ->name('ebook.siap-publish')->middleware('rbac:'.implode(',', [$sa, $admin]));
            Route::post('/{ebook}/publish', [EbookController::class, 'publish'])
                ->name('ebook.publish')->middleware('rbac:'.implode(',', [$sa, $admin]));
        });

        Route::group(['prefix' => 'butuh-review'], function () use ($sa, $reviewer) {
            Route::get('/', [EbookReviewController::class, 'butuhReview'])
                ->name('ebook.butuhreview')->middleware('rbac:'.implode(',', [$sa, $reviewer]));
            Route::get('/sudah', [EbookReviewController::class, 'sudahReview'])
                ->name('ebook.sudahreview')->middleware('rbac:'.implode(',', [$sa, $reviewer]));

            Route::get('/{ebook}', [EbookReviewController::class, 'statusReviewView'])
                ->name('ebook.butuhreview.view')->middleware('rbac:'.implode(',', [$sa, $reviewer]));
            Route::post('/{ebook}', [EbookReviewController::class, 'statusReviewAction'])
                ->name('ebook.butuhreview.action')->middleware('rbac:'.implode(',', [$sa, $reviewer]));
        });
    });
    Route::resource('ebook', EbookController::class)->except(['create', 'store']);

    Route::get('/rekapitulasi/cetakan', [RekapitulasiController::class, 'cetakan'])->name('rekapitulasi.cetakan');
    Route::get('/rekapitulasi/keuangan', [RekapitulasiController::class, 'keuangan'])->name('rekapitulasi.keuangan');
    Route::get('/rekapitulasi/keuangan-tahunan', [RekapitulasiController::class, 'keuanganTahunan'])->name('rekapitulasi.keuangan-tahunan');
    Route::get('/rekapitulasi/export-keuangan', [RekapitulasiController::class, 'exportKeuangan'])->name('rekapitulasi.export-keuangan');
    Route::get('/rekapitulasi-detail/keuangan', [RekapitulasiDetailController::class, 'keuangan'])->name('rekapitulasi-detail.keuangan');
    Route::get('/rekapitulasi-detail/export-keuangan', [RekapitulasiDetailController::class, 'exportKeuangan'])->name('rekapitulasi-detail.export-keuangan');
    Route::get('/rekapitulasi/export-cetakan', [RekapitulasiController::class, 'exportCetakan'])->name('rekapitulasi.export-cetakan');

    Route::get('/notification/{notification}', [NotificationController::class, 'read'])->name('notification.read');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notification');

    // Route::get('/feedback/list', [FeedbackController::class, 'indexAction'])->name('feedback.index');
    // Route::get('/feedback/give', [FeedbackController::class, 'formAction'])->name('feedback.form');
    // Route::post('/feedback/give', [FeedbackController::class, 'storeAction'])->name('feedback.form-store');

    Route::get('/feedback', function () {
        return redirect()->to('https://bit.ly/testingsistempenerbitan');
    })->name('feedback.index');

    Route::get('/manual-book', [ManualController::class, 'index'])->name('manual-book');

    Route::get('profile', [ProfileController::class, 'me'])->name('profile.me');
    Route::put('profile', [ProfileController::class, 'updateMe'])->name('profile.update-me');

    Route::get('template-penulisan', [MasterController::class, 'index'])->name('template-penulisan.index');
    Route::get('template-penulisan/download', [MasterController::class, 'download'])->name('template-penulisan.download');
    Route::post('template-penulisan', [MasterController::class, 'store'])->name('template-penulisan.store');
});

// Authentication Route
Route::get('/login', [AuthenticationController::class, 'logonView'])->name('login');
Route::post('/login', [AuthenticationController::class, 'logonAction'])->name('login.action');
Route::post('/logout', [AuthenticationController::class, 'logoutAction'])->name('logout.action');

// Authentication Route
Route::get('/register', [RegistrationController::class, 'view'])->name('register');
Route::post('/register', [RegistrationController::class, 'action'])->name('register.action');
