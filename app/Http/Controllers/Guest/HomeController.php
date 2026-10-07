<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Stat;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $campaigns = Campaign::query()->published()->ordered()->with('media')->get();

        return inertia('Welcome', [
            'page' => Page::intro('home'),
            'hero' => [
                'title_tail' => Setting::get('home_hero_title_tail', 'Media Games.'),
                'secondary' => Setting::get('home_hero_secondary'),
            ],
            'campaigns' => CampaignResource::collection($campaigns)->resolve(),
            'stats' => Stat::query()->orderBy('sort_order')->orderBy('id')->get(['id', 'value', 'label']),
        ]);
    }
}
