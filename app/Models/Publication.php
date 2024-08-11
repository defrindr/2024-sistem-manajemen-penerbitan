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
        'numberOfPrinting',
        'productionYear',
        'totalProduction',
        'price',
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class, 'themeId');
    }
}
