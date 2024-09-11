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
        return asset('storage/'.Theme::PATH.'/'.$this->cover);
    }

    public static function list()
    {
        $publications = Publication::orderBy('created_at', 'desc')->get();
        $listPublications = [];

        $currentIndex = 0;
        $currentItem = 0;

        foreach ($publications as $publication) {
            if ($currentItem == 4) {
                $currentIndex++;
                $currentItem = 0;
            }

            $listPublications[$currentIndex][$currentItem] = $publication;
            $currentItem++;
        }

        return $listPublications;
    }
}
