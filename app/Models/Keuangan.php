<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    use HasFactory;
    protected $table = 'keuangans';

    protected $fillable = [
        'publicationId',
        'themeId',
        'title',
        'income',
        'productionCost',
        'percentAdmin',
        'percentReviewer',
    ];

    /**
     * Returns the theme that this keuangan belongs to.
     *
     * @return \App\Models\Theme
     */
    public function theme()
    {
        return $this->belongsTo(Theme::class, "themeId");
    }

    /**
     * Returns the details related to this keuangan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(KeuanganDetail::class, "keuanganId");
    }

    /**
     * Returns the publication that this keuangan belongs to.
     *
     * @return \App\Models\Publication
     */
    public function publication()
    {
        return $this->belongsTo(Publication::class, "publicationId");
    }
}
