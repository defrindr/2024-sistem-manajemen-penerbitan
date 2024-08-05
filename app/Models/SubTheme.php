<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTheme extends Model
{
    use HasFactory;

    protected $table = 'subtheme_recommendations';

    protected $fillable = [
        'themeId',
        'name',
        // 'description',
        // 'reviewer1Id',
        // 'reviewer2Id',
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class, "themeId");
    }

    public function ebook()
    {
        return $this->hasMany(Ebook::class, "subThemeId");
    }

    public function isThemeOpen(): bool
    {
        return $this->theme->status === Theme::STATUS_OPEN;
    }

    public function acceptEbook(): Ebook | null
    {
        return $this
            ->ebook()
            ->where('status', '=', 'accept')
            ->first();
    }

    public function hasAuthorRegistered(): bool
    {
        return $this
            ->ebook()
            ->where('status', '!=', 'pending')
            ->exists();
    }
}
