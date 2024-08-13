<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbookReview extends Model
{
    use HasFactory;

    protected $table = 'ebook_reviews';

    protected $fillable = ['reviewerId', 'ebookId', 'acc', 'comment'];

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewerId');
    }

    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class, 'ebookId');
    }

    public function getStatusDetailAttribute()
    {
        $statusLabel = "Pending";

        switch ($this->acc) {
            case -1:
                $statusLabel = "Ditolak";
                break;
            case 1:
                $statusLabel = "Diterima";
                break;
            default:
                $statusLabel = "Pending";
                break;
        }

        $statusLabel .= "<br/><i>" . $this->comment . "</i>";


        return $statusLabel;
    }

    public static function needReviewCount($userId)
    {
        return EbookReview::where('reviewerId', $userId)
            ->where(function ($qb) {
                $qb->where('acc', 0)
                    ->orWhere('acc', -1);
            })
            ->count();
    }
}
