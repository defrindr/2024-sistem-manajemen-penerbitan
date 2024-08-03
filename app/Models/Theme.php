<?php

namespace App\Models;

use App\Helpers\StrHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Theme extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'theme_recommendations';

    const STATUS_DRAFT = 'draft';

    const STATUS_OPEN = 'open';

    const STATUS_REVIEW = 'review';

    const STATUS_CLOSE = 'close';

    const STATUS_PUBLISH = 'publish';

    protected $fillable = [
        'name',
        'description',
        'dueDate',
        'status',
        'isbn',
        'price',
    ];

    public function subThemes(): HasMany
    {
        return $this->hasMany(SubTheme::class, 'themeId');
    }

    public function ebooks(): HasMany
    {
        return $this->hasMany(Ebook::class, 'themeId');
    }

    public function hasEbook(): bool
    {
        return $this->ebooks()->exists();
    }

    public function doesntHaveEbook(): bool
    {
        return $this->ebooks()->where('userId', auth()->id())->exists();
    }

    public function getDueDateFormattedAttribute()
    {
        return date('d F Y', strtotime($this->dueDate));
    }


    public function getPriceFormattedAttribute()
    {
        return StrHelper::currency($this->price, "Rp");
    }

    public function getStatusFormattedAttribute()
    {
        $labels = [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_OPEN => 'Dibuka',
            self::STATUS_REVIEW => 'Tahap review',
            self::STATUS_CLOSE => 'Tutup',
        ];

        return isset($labels[$this->status]) ? $labels[$this->status] : '-';
    }
}
