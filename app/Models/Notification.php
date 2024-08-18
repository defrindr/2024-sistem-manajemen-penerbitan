<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';

    protected $fillable = [
        'description',
        'isRead',
        'ebookId',
        'userId',
    ];

    /**
     * Get the ebook that owns the notification.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @property int $ebookId The ID of the related ebook in the ebooks table.
     */
    public function ebook()
    {
        return $this->belongsTo(Ebook::class, 'ebookId');
    }

    /**
     * Get the user that owns the notification.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     *
     * @property int $userId The ID of the related user in the users table.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public static function getUnReadFromDesc()
    {
        return self::where('isRead', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
    public static function getUnReadFromAscCount()
    {
        return self::where('isRead', false)
            ->orderBy('created_at', 'asc')
            ->count();
    }

    public function getDescriptionMinAttribute()
    {
        return substr($this->description, 0, 15) . '...';
    }
    /**
     * Get the timelapse since the notification was created.
     *
     * @return string The timelapse in a human-readable format.
     */
    public function getTimelapseAttribute()
    {
        $now = now();
        $created_at = $this->created_at;
        $diff = $now->diff($created_at);

        if ($diff->y > 0) {
            return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
        } elseif ($diff->m > 0) {
            return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
        } elseif ($diff->d > 0) {
            return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        } elseif ($diff->i > 0) {
            return $diff->i . ' min' . ($diff->i > 1 ? 's' : '') . ' ago';
        } else {
            return 'Just now';
        }
    }
}
