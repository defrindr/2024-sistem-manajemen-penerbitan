<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Ebook extends Model
{
    use HasFactory;

    const STATUS_PAYMENT = 'payment';

    const STATUS_PENDING = 'pending';

    const STATUS_DRAFT = 'draft';

    const STATUS_SUBMIT = 'submit';

    const STATUS_REVIEW = 'review';

    const STATUS_ACCEPT = 'accept';

    const STATUS_PUBLISH = 'publish';

    const STATUS_NOT_ACCEPT = 'not_accept';

    const FILE_PATH = 'ebooks';

    protected $table = 'ebooks';

    protected $fillable = [
        'themeId',
        'subthemeId',
        'userId',
        'title',
        'draft',
        'dueDate',
        'proofOfPayment',
        'royalty',
        'status',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'themeId');
    }

    public function subTheme(): BelongsTo
    {
        return $this->belongsTo(SubTheme::class, 'subthemeId');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(EbookReview::class, 'ebookId');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return date('d F Y, H:i A', strtotime($this->created_at));
    }

    public function getDraftPathAttribute()
    {
        $path = self::FILE_PATH;

        return asset("storage/$path/{$this->draft}");
    }

    public function getProofOfPaymentPathAttribute()
    {
        $path = self::FILE_PATH;

        return asset("storage/$path/{$this->proofOfPayment}");
    }

    /**
     * Retrieves ebook data as chart data based on the creation date.
     *
     * This function groups the ebook records by the month of their creation date and counts the total number of ebooks for each month.
     * The result is returned as a collection of objects, where each object contains the month (as an integer) and the total count of ebooks.
     *
     * @return array An array of objects with 'month' and 'total' properties.
     */
    public static function chartData()
    {
        $data = Ebook::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
            ->groupBy('month')
            ->get()
            ->toArray();

        // generate dummy data
        for ($i = 1; $i <= 12; $i++) {
            if (! in_array($i, array_column($data, 'month'))) {
                $data[] = ['month' => $i, 'total' => 0];
            }
        }

        // sort data
        usort($data, function ($a, $b) {
            return $a['month'] - $b['month'];
        });

        // return data
        return $data;
    }

    public static function chartDataPenjualanBuku($year = null)
    {
        if (! $year) {
            $year = date('Y');
        }
        $raws = Keuangan::whereIn('themeId', Ebook::where('userId', Auth::user()->id)->select('themeId'))->get();
        $datasets = [];
        $themes = Theme::whereIn('id', Ebook::where('userId', Auth::user()->id)->select('themeId'))->get();
        // Year list with -3 year until current
        $labels = range(date('Y') - 3, date('Y'));

        foreach ($themes as $theme) {
            $color = '#'.str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

            $data = [];
            foreach ($labels as $year) {
                $keuangan = Keuangan::where('themeId', $theme->id)->where('year', $year)->groupBy('year')->select(
                    DB::raw('sum(sellCount) as sellPrice')
                )->first();

                if ($keuangan) {
                    $data[] = $keuangan->sellPrice;
                } else {
                    $data[] = 0;
                }
            }

            $datasets[] = [
                'label' => $theme->name,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'data' => $data,
            ];
        }

        return compact('labels', 'datasets', 'year');
    }

    /**
     * Retrieves the count of draft ebooks for a given user.
     *
     * @param  int  $userId  The ID of the user.
     * @return int The count of draft ebooks.
     */
    public static function draftCount($userId)
    {
        return Ebook::where('userId', $userId)->whereNotIn('status', [Ebook::STATUS_ACCEPT, Ebook::STATUS_PUBLISH])->count();
    }

    public static function publishCount($userId)
    {
        return Ebook::where('userId', $userId)->whereIn('status', [Ebook::STATUS_ACCEPT, Ebook::STATUS_PUBLISH])->count();
    }
}
