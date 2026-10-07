<?php

namespace App\Support;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Strict contact-form limits that only count submissions that passed validation,
 * so a typo or a failed reCAPTCHA never uses up someone's allowance.
 *
 * The looser per-minute `contact` throttle on the route still guards against floods.
 */
class ContactSubmissionLimiter
{
    private const BURST_SECONDS = 10;

    private const PER_IP_PER_HOUR = 3;

    private const PER_EMAIL_PER_HOUR = 2;

    /**
     * @throws ValidationException when any limit is already used up
     */
    public function ensureAllowed(Request $request, string $email): void
    {
        $wait = collect($this->limits($request, $email))
            ->filter(fn (Limit $limit) => RateLimiter::tooManyAttempts($limit->key, $limit->maxAttempts))
            ->map(fn (Limit $limit) => RateLimiter::availableIn($limit->key))
            ->max();

        if ($wait === null) {
            return;
        }

        $when = match (true) {
            $wait >= 120 => 'in about '.(int) ceil($wait / 60).' minutes',
            $wait >= 60 => 'in about a minute',
            default => 'in a few seconds',
        };

        throw ValidationException::withMessages([
            'throttle' => "You've sent a few messages already. Please try again {$when}.",
        ]);
    }

    /**
     * Record a submission against every limit.
     */
    public function hit(Request $request, string $email): void
    {
        foreach ($this->limits($request, $email) as $limit) {
            RateLimiter::hit($limit->key, $limit->decaySeconds);
        }
    }

    /**
     * @return list<Limit>
     */
    private function limits(Request $request, string $email): array
    {
        $email = Str::lower(trim($email));

        return [
            (new Limit('contact-burst:'.$request->ip(), 1, self::BURST_SECONDS)),
            (new Limit('contact-ip:'.$request->ip(), self::PER_IP_PER_HOUR, 3600)),
            (new Limit('contact-email:'.sha1($email), self::PER_EMAIL_PER_HOUR, 3600)),
        ];
    }
}
