<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roleId',
        'phone',
        'bio',
        'npwp',
        'categoryId',
        'ktp',
        'bank',
        'noRekening',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'roleId');
    }

    public function roleIn($roles)
    {
        if (in_array($this->role->name, $roles)) {
            return true;
        }

        return false;
    }

    public static function ScopeReviewerWithCategory($query, $categoryId = null)
    {
        $query->where([
            'roleId' => Role::findIdByName(Role::REVIEWER),
        ]);

        if ($categoryId) {
            $query->where([
                'categoryId' => $categoryId,
            ]);
        }

        return $query;
    }

    public static function authorCount()
    {
        return User::where('roleId', Role::findIdByName(Role::AUTHOR))->count();
    }
}
