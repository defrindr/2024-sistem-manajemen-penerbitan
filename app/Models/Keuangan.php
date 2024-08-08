<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;
    protected $table = 'keuangans';

    protected $fillable = [
        'themeId',
        'title',
        'income',
        'productionCost',
        'percentAdmin',
        'percentReviewer',
    ];

    public function theme()
    {
        return $this->belongsTo(Theme::class, "themeId");
    }

    public function details()
    {
        return $this->hasMany(KeuanganDetail::class, "keuanganId");
    }
}
