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
        'reviewer1Id',
        'reviewer2Id',
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function ebook()
    {
        return $this->hasMany(Ebook::class);
    }
}
