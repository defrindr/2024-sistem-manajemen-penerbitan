<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeuanganDetail extends Model
{
    use HasFactory;

    protected $table = 'keuangan_details';
    protected $fillable = [
        'keuanganId',
        'userId',
        'role',
        'profit',
    ];

    public function keuangan()
    {
        return $this->belongsTo(Keuangan::class, "keuanganId");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "userId");
    }
}
