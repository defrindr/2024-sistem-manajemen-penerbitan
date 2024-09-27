<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\EbookReview;
use App\Models\Publication;
use App\Models\Role;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Checks if the authenticated user is a super admin or admin and returns the dashboard accordingly.
     *
     * @return mixed
     */
    public function index()
    {
        // check if user is super admin or admin
        $roleId = Auth::user()->roleId;

        //  check user based on role, return view accordingly
        switch ($roleId) {
            case Role::findIdByName(Role::ADMINISTRATOR):
            case Role::findIdByName(Role::SUPERADMIN):
                return $this->dashboardAdmin();
            case Role::findIdByName(Role::AUTHOR):
                return $this->dashboardAuthor();
            default:
                return $this->dashboardReviewer();
        }
    }

    /**
     * Retrieves the total number of authors, published themes, and chart data for ebooks grouped by month.
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function dashboardAdmin()
    {
        // get total authors
        $totalAuthors = User::authorCount();

        // published themes
        $publishedThemes = Theme::publishedCount();

        // chart ebook, group by per bulan
        $ebooks = Ebook::chartData();

        $listPublications = Publication::list();

        return view('dashboard.index', compact('totalAuthors', 'publishedThemes', 'ebooks', 'listPublications'));
    }

    /**
     * Retrieves the dashboard data for an author.
     *
     * @return \Illuminate\Contracts\View\View
     */
    private function dashboardAuthor()
    {
        $userId = Auth::user()->id;

        // get total theme open
        $totalThemeOpen = Theme::openCount();

        // get my ebooks draft
        $myEbooksDraft = Ebook::draftCount($userId);

        // get my ebooks publish
        $myEbooksPublish = Ebook::publishCount($userId);
        // chart ebook, group by per bulan
        $ebooksChart = Ebook::chartDataPenjualanBuku();

        $listPublications = Publication::list();

        return view('dashboard.author', compact('totalThemeOpen', 'myEbooksDraft', 'myEbooksPublish', 'ebooksChart', 'listPublications'));
    }

    public function dashboardReviewer()
    {
        $userId = Auth::user()->id;

        // ebooks need review by reviewer
        $ebooksNeedReview = EbookReview::needReviewCount($userId);
        $listPublications = Publication::list();

        return view('dashboard.reviewer', compact('ebooksNeedReview', 'listPublications'));
    }
}
