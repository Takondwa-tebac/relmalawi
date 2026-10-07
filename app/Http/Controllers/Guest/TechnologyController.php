<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Page;
use Inertia\Response;

class TechnologyController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Technology', [
            'page' => Page::intro('technology'),
            'features' => Feature::groupedForPage('technology'),
        ]);
    }
}
