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
        'dueDate',
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
        if ($this->theme->multipleAuthor) {
            return $this->theme->ebook()->exists();
        }

        return $this
            ->ebook()
            // ->where('status', '!=', 'pending')
            ->exists();
    }

    public function getDueDateFormattedAttribute()
    {
        return date('d F Y', strtotime($this->dueDate));
    }

    public function isNotDeadline()
    {
        return strtotime($this->dueDate) >= time();
    }
}
