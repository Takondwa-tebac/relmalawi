<?php

namespace App\Http\Middleware;

use App\Models\Page;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns a 404 for a public page that has been switched off in the CMS.
 *
 * Signed-in staff (super-admin / editor) can still open an inactive page with GET
 * so they can preview it before switching it on; the layout shows them a warning strip.
 *
 * Usage: ->middleware('page.active:about')
 */
class EnsurePageIsActive
{
    public function handle(Request $request, Closure $next, string $slug): Response
    {
        if (Page::isActive($slug)) {
            return $next($request);
        }

        $isStaff = $request->user()?->hasAnyRole(['super-admin', 'editor']) ?? false;

        if ($isStaff && $request->isMethodSafe()) {
            $request->attributes->set('previewing_inactive_page', true);

            return $next($request);
        }

        abort(404);
    }
}
