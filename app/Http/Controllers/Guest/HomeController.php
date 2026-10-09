<?php

namespace App\Http\Controllers\Guest;

use App\Enums\PartnerType;
use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
use App\Http\Resources\PartnerResource;
use App\Models\Campaign;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Setting;
use App\Models\Stat;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $campaigns = Campaign::query()->published()->ordered()->with('media')->get();

        $partners = Partner::query()->published()->ordered()->with('media')->get()
            ->filter(fn (Partner $partner) => $partner->hasMedia('logo'));

        return inertia('Welcome', [
            'page' => Page::intro('home'),
            'hero' => [
                'title_tail' => Setting::get('home_hero_title_tail', 'Media Games.'),
                'secondary' => Setting::get('home_hero_secondary'),
            ],
            'campaigns' => CampaignResource::collection($campaigns)->resolve(),
            'stats' => Stat::query()->orderBy('sort_order')->orderBy('id')->get(['id', 'value', 'label']),
            'features' => Feature::groupedForPage('home'),
            'mediaPartners' => PartnerResource::collection(
                $partners->reject(fn (Partner $partner) => $partner->type === PartnerType::MobileMoney)->values()
            )->resolve(),
            'paymentPartners' => PartnerResource::collection(
                $partners->filter(fn (Partner $partner) => $partner->type === PartnerType::MobileMoney)->values()
            )->resolve(),
            'faqs' => Faq::query()->published()->forHome()->ordered()->get(['id', 'category', 'question', 'answer']),
        ]);
    }
}
