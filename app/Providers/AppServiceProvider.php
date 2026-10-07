<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\RolePolicy;
use App\Support\MailLogo;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureAuthorization();
        $this->configureRateLimiting();

        Event::listen(MessageSending::class, MailLogo::class);
    }

    /**
     * Contact form limits: 1 per 10 seconds and 3 per hour per IP, 2 per hour per email.
     */
    protected function configureRateLimiting(): void
    {
        // Loose flood guard for every POST (including invalid ones). The strict per-IP and
        // per-email limits live in ContactSubmissionLimiter and only count valid submissions.
        RateLimiter::for('contact', fn (Request $request) => Limit::perMinute(20)->by('contact-flood:'.$request->ip()));
    }

    /**
     * Super-admins pass every gate check; everyone else falls through to policies.
     */
    protected function configureAuthorization(): void
    {
        Gate::before(function (?User $user, string $ability, array $arguments) {
            if (! $user?->hasRole('super-admin')) {
                return null;
            }

            // Self-deletion and built-in role deletion are decided by the policies.
            $subject = $arguments[0] ?? null;
            if ($ability === 'delete' && ($subject instanceof User || $subject instanceof Role)) {
                return null;
            }

            return true;
        });

        // Spatie's Role lives outside App\Models so it is not auto-discovered.
        Gate::policy(Role::class, RolePolicy::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
