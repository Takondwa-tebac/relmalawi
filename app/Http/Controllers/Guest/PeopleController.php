<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamMemberResource;
use App\Models\Feature;
use App\Models\Page;
use App\Models\TeamMember;
use Inertia\Response;

class PeopleController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('People', [
            'page' => Page::intro('people'),
            'features' => Feature::groupedForPage('people'),
            'team' => TeamMemberResource::collection(
                TeamMember::query()->published()->ordered()->with('media')->get()
            )->resolve(),
        ]);
    }
}
