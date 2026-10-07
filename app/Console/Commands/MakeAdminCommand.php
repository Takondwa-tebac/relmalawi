<?php

namespace App\Console\Commands;

use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[Signature('cms:make-admin {email : Email address of the user} {--role=super-admin : Role to assign}')]
#[Description('Create a CMS user, or promote an existing one, to a role')]
class MakeAdminCommand extends Command
{
    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $roleName = (string) $this->option('role');

        if (Validator::make(['email' => $email], ['email' => 'required|email'])->fails()) {
            $this->components->error('The email address is not valid.');

            return self::FAILURE;
        }

        // Make sure roles and permissions exist (idempotent).
        $this->callSilently('db:seed', ['--class' => RolesSeeder::class, '--force' => true]);

        if (! Role::where('name', $roleName)->where('guard_name', 'web')->exists()) {
            $this->components->error("Role [{$roleName}] does not exist.");

            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if ($user === null) {
            $name = text('Name', required: true);
            $secret = password('Password', required: true, validate: fn (string $value) => strlen($value) < 8
                ? 'The password must be at least 8 characters.'
                : null);

            $user = User::create(['name' => $name, 'email' => $email, 'password' => $secret]);
            $user->forceFill(['email_verified_at' => now()])->save();

            $this->components->info("Created user {$email}.");
        }

        $user->assignRole($roleName);

        $this->components->info("{$email} now has the {$roleName} role.");

        return self::SUCCESS;
    }
}
