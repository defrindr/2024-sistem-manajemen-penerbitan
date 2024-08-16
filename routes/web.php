<?php

use App\Helpers\CheckDeadline;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\EbookReviewController;
use App\Http\Controllers\kategoriController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RekapitulasiController;
use App\Http\Controllers\SubThemeController;
use App\Http\Controllers\ThemeController;
use App\Models\Keuangan;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    // Check Deadline
    CheckDeadline::run();

    $admin = Role::ADMINISTRATOR;
    $sa = Role::SUPERADMIN;
    $author = Role::AUTHOR;
    $reviewer = Role::REVIEWER;

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('category', kategoriController::class)->except(['show'])->names('kategori');
    Route::get('/topik/unduh', [ThemeController::class, 'export'])->name('theme.export');
    Route::resource('topik', ThemeController::class)
        ->names('theme')
        ->parameters(['topik' => 'theme']);


    Route::resource('topik.keuangan', KeuanganController::class)
        ->names('theme.keuangan')
        ->parameters(['topik' => 'theme'])->except(['update', 'edit']);

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


    Route::group(['prefix' => 'ebook'], function () use ($sa, $author, $reviewer, $admin) {
        Route::get('/butuh-konfirmasi-pembayaran/list', [EbookController::class, 'konfirmasiPembayaranList'])
            ->name('ebook.konfirmasi-pembayaran-list')->middleware('rbac:' . implode(',', [$sa, $admin]));

        Route::post('/butuh-konfirmasi-pembayaran/{ebook}/confirm', [EbookController::class, 'konfirmasiPembayaranAction'])
            ->name('ebook.konfirmasi-pembayaran-action')->middleware('rbac:' . implode(',', [$sa, $admin]));

        Route::post('/konfirmasi-pengajuan/{ebook}/confirm', [EbookController::class, 'konfirmasiPengajuanAction'])
            ->name('ebook.konfirmasi-ajukan-action')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::get('/{theme}/{subTheme}/create', [EbookController::class, 'create'])
            ->name('ebook.create')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::post('/{theme}/{subTheme}/store', [EbookController::class, 'store'])
            ->name('ebook.store')->middleware('rbac:' . implode(',', [$sa, $author]));


        Route::get('{ebook}/atur-royalti', [EbookController::class, 'aturRoyalti'])
            ->name('ebook.atur-royalti')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::post('{ebook}/atur-royalti', [EbookController::class, 'aturRoyaltiStore'])
            ->name('ebook.atur-royalti.store')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::get('me', [EbookController::class, 'me'])
            ->name('ebook.me')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::get('progress/{ebook}', [EbookController::class, 'progress'])
            ->name('ebook.progress')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::group(['prefix' => 'siap-publish'], function () use ($sa, $admin) {
            Route::get('/', [EbookController::class, 'siapPublish'])
                ->name('ebook.siap-publish')->middleware('rbac:' . implode(',', [$sa, $admin]));
            Route::post('/{ebook}/publish', [EbookController::class, 'publish'])
                ->name('ebook.publish')->middleware('rbac:' . implode(',', [$sa, $admin]));
        });

        Route::group(['prefix' => 'butuh-review'], function () use ($sa, $reviewer) {
            Route::get('/', [EbookReviewController::class, 'butuhReview'])
                ->name('ebook.butuhreview')->middleware('rbac:' . implode(',', [$sa, $reviewer]));
            Route::get('/sudah', [EbookReviewController::class, 'sudahReview'])
                ->name('ebook.sudahreview')->middleware('rbac:' . implode(',', [$sa, $reviewer]));

            Route::get('/{ebook}', [EbookReviewController::class, 'statusReviewView'])
                ->name('ebook.butuhreview.view')->middleware('rbac:' . implode(',', [$sa, $reviewer]));
            Route::post('/{ebook}', [EbookReviewController::class, 'statusReviewAction'])
                ->name('ebook.butuhreview.action')->middleware('rbac:' . implode(',', [$sa, $reviewer]));
        });
    });
    Route::resource('ebook', EbookController::class)->except(['create', 'store']);


    Route::get('/rekapitulasi/cetakan', [RekapitulasiController::class, 'cetakan'])->name('rekapitulasi.cetakan');
    Route::get('/rekapitulasi/keuangan', [RekapitulasiController::class, 'keuangan'])->name('rekapitulasi.keuangan');
    Route::get('/rekapitulasi/export-keuangan', [RekapitulasiController::class, 'exportKeuangan'])->name('rekapitulasi.export-keuangan');
    Route::get('/rekapitulasi/export-cetakan', [RekapitulasiController::class, 'exportCetakan'])->name('rekapitulasi.export-cetakan');
});

// Authentication Route
Route::get('/login', [AuthenticationController::class, 'logonView'])->name('login');
Route::post('/login', [AuthenticationController::class, 'logonAction'])->name('login.action');
Route::post('/logout', [AuthenticationController::class, 'logoutAction'])->name('logout.action');

// Authentication Route
Route::get('/register', [RegistrationController::class, 'view'])->name('register');
Route::post('/register', [RegistrationController::class, 'action'])->name('register.action');
