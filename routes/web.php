<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\EbookReviewController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ThemeController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    $admin = Role::ADMINISTRATOR;
    $sa = Role::SUPERADMIN;
    $author = Role::AUTHOR;
    $reviewer = Role::REVIEWER;

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'topik'], function () use ($sa, $admin, $reviewer) {

        Route::match(['GET', 'HEAD'], '/', [ThemeController::class, 'index'])
            ->name('theme.index');

        Route::get('create', [ThemeController::class, 'create'])
            ->name('theme.create')
            ->middleware('rbac:' . implode(',', [$sa, $admin]));

        Route::post('/', [ThemeController::class, 'store'])
            ->name('theme.store')
            ->middleware('rbac:' . implode(',', [$sa, $admin]));

        Route::get('{theme}/edit', [ThemeController::class, 'edit'])
            ->name('theme.edit')
            ->middleware('rbac:' . implode(',', [$sa, $admin]));

        Route::post('{theme}/close', [ThemeController::class, 'close'])
            ->name('theme.close');

        Route::post('{theme}/open', [ThemeController::class, 'open'])
            ->name('theme.open');

        Route::post('{theme}/review', [ThemeController::class, 'review'])
            ->name('theme.review');

        Route::match(['PUT', 'PATCH'], '{theme}', [ThemeController::class, 'update'])
            ->name('theme.update')
            ->middleware('rbac:' . implode(',', [$sa, $admin]));

        Route::delete('{theme}', [ThemeController::class, 'destroy'])
            ->name('theme.destroy')
            ->middleware('rbac:' . implode(',', [$sa, $admin]));

        Route::match(['GET', 'HEAD'], '{theme}', [ThemeController::class, 'show'])
            ->name('theme.show')
            ->middleware('rbac:' . implode(',', [$sa, $admin, $reviewer]));
    });

    Route::group(['prefix' => 'ebook'], function () use ($sa, $author, $reviewer, $admin) {
        Route::get('{theme}/create', [EbookController::class, 'create'])
            ->name('ebook.create')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::post('{theme}', [EbookController::class, 'store'])
            ->name('ebook.store')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::get('{ebook}/edit', [EbookController::class, 'edit'])
            ->name('ebook.edit')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::get('{ebook}/atur-royalti', [EbookController::class, 'aturRoyalti'])
            ->name('ebook.atur-royalti')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::post('{ebook}/atur-royalti', [EbookController::class, 'aturRoyaltiStore'])
            ->name('ebook.atur-royalti.store')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::match(['PUT', 'PATCH'], '{ebook}/update', [EbookController::class, 'update'])
            ->name('ebook.update')->middleware('rbac:' . implode(',', [$sa, $author]));

        Route::get('me', [EbookController::class, 'me'])
            ->name('ebook.me')->middleware('rbac:' . implode(',', [$sa, $author]));

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
});

// Authentication Route
Route::get('/login', [AuthenticationController::class, 'logonView'])->name('login');
Route::post('/login', [AuthenticationController::class, 'logonAction'])->name('login.action');
Route::post('/logout', [AuthenticationController::class, 'logoutAction'])->name('logout.action');

// Authentication Route
Route::get('/register', [RegistrationController::class, 'view'])->name('register');
Route::post('/register', [RegistrationController::class, 'action'])->name('register.action');
