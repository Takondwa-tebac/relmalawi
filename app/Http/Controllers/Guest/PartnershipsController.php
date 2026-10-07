<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Models\Page;
use App\Models\Partner;
use Inertia\Response;

class PartnershipsController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Partnerships', [
            'page' => Page::intro('partnerships'),
            'partners' => PartnerResource::collection(
                Partner::query()->published()->ordered()->with('media')->get()
            )->resolve(),
        ]);
    }
}
