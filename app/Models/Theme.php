<?php

namespace App\Models;

use App\Helpers\StrHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Theme extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'theme_recommendations';

    const STATUS_DRAFT = 'draft';

    const STATUS_OPEN = 'open';

    const STATUS_REVIEW = 'review';

    const STATUS_CLOSE = 'close';

    const STATUS_PUBLISH = 'publish';

    const PATH = 'theme';

    protected $fillable = [
        'name',
        'description',
        'status',
        'isbn',
        'file',
        'cover',
        'price',
        'multipleAuthor',
        'dueDate',
        'categoryId',
        'reviewer1Id',
        'reviewer2Id',
    ];

    public function subThemes(): HasMany
    {
        return $this->hasMany(SubTheme::class, 'themeId');
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class, 'themeId');
    }

    public function ebooks(): HasMany
    {
        return $this->hasMany(Ebook::class, 'themeId');
    }

    public function reviewer1()
    {
        return $this->belongsTo(User::class, 'reviewer1Id');
    }

    public function reviewer2()
    {
        return $this->belongsTo(User::class, 'reviewer2Id');
    }

    public function hasEbook(): bool
    {
        return $this->ebooks()->exists();
    }

    public function doesntHaveEbook(): bool
    {
        return $this->ebooks()->where('userId', Auth::id())->exists();
    }

    public function getPriceFormattedAttribute()
    {
        return StrHelper::currency($this->price, 'Rp');
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

    public function pathToFile($attribute)
    {
        $path = '';

        switch ($attribute) {
            case 'cover':
                $path = $this->cover;
                break;
            case 'file':
                $path = $this->file;
                break;
            default:
                break;
        }

        return asset('storage/'.self::PATH.'/'.$path);
    }

    public static function publishedCount()
    {
        return self::where('status', self::STATUS_PUBLISH)->count();
    }

    public static function openCount()
    {
        return self::where('status', self::STATUS_OPEN)->count();
    }

    public function getAuthorsAttribute()
    {
        $names = [];

        $userIds = Ebook::where('themeId', $this->id)->groupBy('userId')->select('userId');

        $items = User::whereIn('id', $userIds)->get();

        foreach ($items as $item) {
            $names[] = $item->name;
        }

        return implode(' | ', $names);
    }
}
