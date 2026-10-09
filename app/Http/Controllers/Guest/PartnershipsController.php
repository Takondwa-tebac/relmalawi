<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Models\Feature;
use App\Models\Page;
use App\Models\Partner;
use Inertia\Response;

class PartnershipsController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Partnerships', [
            'page' => Page::intro('partnerships'),
            'features' => Feature::groupedForPage('partnerships'),
            // Radio / Television / Radio & Television only; mobile-money partners have their own row.
            'partners' => PartnerResource::collection(
                Partner::query()->published()->media()->ordered()->with('media')->get()
            )->resolve(),
            'payment_partners' => PartnerResource::collection(
                Partner::query()->published()->mobileMoney()->ordered()->with('media')->get()
            )->resolve(),
        ]);
    }
}
