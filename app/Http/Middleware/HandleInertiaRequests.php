<?php

namespace App\Http\Middleware;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),

            ],
            'site' => fn () => [
                'banner_text' => Setting::get('banner_text', "REL is building the infrastructure behind Malawi's next media games."),
                'contact_email' => Setting::get('contact_email', 'hello@relmalawi.com'),
                'footer_name' => Setting::get('footer_name', 'Radio Entertainment Limited'),
                'est_year' => Setting::get('est_year', '2024'),
                'codes_count' => (int) Setting::get('codes_count', '40'),
                'codes_pattern' => Setting::get('codes_pattern', '*4342*{n}#'),
            ],
            // Which public pages are switched on, for the navbar, footer and buttons.
            'nav' => fn () => Page::navigation(),
            'activePaths' => fn () => Page::activePaths(),
            'contactEnabled' => fn () => Page::isActive('contact'),
            'previewingInactive' => fn () => (bool) $request->attributes->get('previewing_inactive_page', false),
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
