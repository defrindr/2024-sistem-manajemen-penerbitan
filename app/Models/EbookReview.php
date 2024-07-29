<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EbookReview extends Model
{
    use HasFactory;

    protected $table = 'ebook_reviews';

    protected $fillable = ['reviewerId', 'ebookId', 'acc'];

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewerId');
    }

    public function ebook(): BelongsTo
    {
        return $this->belongsTo(Ebook::class, 'ebookId');
    }
}
