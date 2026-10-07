<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Page;
use Inertia\Response;

class AboutController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('About', [
            'page' => Page::intro('about'),
            'features' => Feature::groupedForPage('about'),
        ]);
    }
}
