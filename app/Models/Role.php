<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const SUPERADMIN = 'Super Administrator';

    const ADMINISTRATOR = 'Administrator';

    const AUTHOR = 'Author';

    const REVIEWER = 'Reviewer';

    protected $table = 'roles';

    protected $fillable = [
        'name',
    ];

    public static function findIdByName(string $roleName): int
    {
        $role = self::where('name', $roleName)->first();
        if (! $role) {
            return -1;
        }

        return $role->id;
    }
}
