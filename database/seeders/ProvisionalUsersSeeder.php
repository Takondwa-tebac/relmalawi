<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

/**
 * Provisional accounts so a fresh local install can log straight into /admin.
 *
 * Never runs in production: create real admins there with `php artisan cms:make-admin`.
 * The password is read from SEED_USER_PASSWORD in .env (there is deliberately no default).
 */
class ProvisionalUsersSeeder extends Seeder
{
    /**
     * @var array<string, array{name: string, role: string}>
     */
    private const USERS = [
        'admin@rel.test' => ['name' => 'REL Super Admin', 'role' => 'super-admin'],
        'editor@rel.test' => ['name' => 'REL Editor', 'role' => 'editor'],
    ];

    public function run(): void
    {
        if (app()->isProduction()) {
            logger()->notice('Skipped provisional users in production. Use cms:make-admin instead.');

            return;
        }

        $password = config('cms.seed_user_password');

        if (blank($password)) {
            throw new RuntimeException('Set SEED_USER_PASSWORD in .env before seeding the provisional users (then run `php artisan config:clear` if config is cached).');
        }

        $this->call(RolesSeeder::class);

        foreach (self::USERS as $email => $details) {
            $user = User::query()->updateOrCreate(
                ['email' => $email],
                [
                    'name' => $details['name'],
                    'password' => $password,
                    'email_verified_at' => now(),
                ],
            );

            $user->syncRoles([$details['role']]);
        }
    }
}
