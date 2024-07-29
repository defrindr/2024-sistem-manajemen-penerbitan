<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ebook extends Model
{
    use HasFactory;

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
        'userId',
        'title',
        'draft',
        'royalty',
        'status',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'themeId');
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
}
