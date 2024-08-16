<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $table = 'publications';
    protected $fillable = [
        'themeId',
        'title',
        'cover',
        'numberOfPrinting',
        'productionYear',
        'totalProduction',
        'price',
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class, 'themeId');
    }

    public function keuangans()
    {
        return $this->hasMany(Keuangan::class, 'publicationId');
    }

    public function getCoverLinkAttribute()
    {
        return asset("storage/" . Theme::PATH . "/" . $this->cover);
    }
}
