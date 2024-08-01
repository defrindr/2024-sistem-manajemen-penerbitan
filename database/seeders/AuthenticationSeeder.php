<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuthenticationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [Role::SUPERADMIN, Role::ADMINISTRATOR, Role::AUTHOR, Role::REVIEWER];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'roleId' => Role::findIdByName(Role::SUPERADMIN),
        ]);

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'roleId' => Role::findIdByName(Role::ADMINISTRATOR),
        ]);

        for ($i = 1; $i <= 10; $i++) {
            User::factory()->create([
                'name' => Role::AUTHOR . " " . $i,
                'email' => "author$i@example.com",
                'npwp' => "12736615263187236",
                'phone' => "62851625354$i",
                'bio' => "This is author $i",
                'roleId' => Role::findIdByName(Role::AUTHOR),
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            User::factory()->create([
                'name' => Role::REVIEWER . " " . $i,
                'email' => "reviewer$i@example.com",
                'npwp' => "127{$i}615263187236",
                'phone' => "628516{$i}5354$i",
                'bio' => "This is author $i",
                'roleId' => Role::findIdByName(Role::REVIEWER),
            ]);
        }
    }
}
