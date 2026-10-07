<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Server-side Google reCAPTCHA v3 verification (score based).
 *
 * Skipped only in local/testing when no keys are configured; anywhere else a
 * missing configuration fails closed.
 */
class RecaptchaV3 implements ValidationRule
{
    /** Run even when the token is empty so a missing token is rejected. */
    public bool $implicit = true;

    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct(private readonly string $action = 'contact') {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.recaptcha.secret');
        $siteKey = config('services.recaptcha.site_key');

        if (blank($secret) || blank($siteKey)) {
            if (app()->environment(['local', 'testing'])) {
                return;
            }

            Log::warning('reCAPTCHA keys are not configured (RECAPTCHA_SITE_KEY / RECAPTCHA_SECRET_KEY); contact form submissions are being rejected.');
            $fail('We could not verify your submission right now. Please try again later.');

            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('We could not verify that you are human. Please reload the page and try again.');

            return;
        }

        try {
            $result = Http::asForm()
                ->timeout(5)
                ->connectTimeout(3)
                ->post(self::VERIFY_URL, [
                    'secret' => $secret,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ])
                ->throw()
                ->json();
        } catch (Throwable $e) {
            report($e);
            $fail('We could not verify your submission right now. Please try again in a moment.');

            return;
        }

        $passed = ($result['success'] ?? false) === true
            && ($result['action'] ?? null) === $this->action
            && (float) ($result['score'] ?? 0) >= (float) config('services.recaptcha.min_score', 0.5);

        if (! $passed) {
            $fail('Our spam check flagged this submission. Please try again, or email us directly if the problem continues.');
        }
    }
}
