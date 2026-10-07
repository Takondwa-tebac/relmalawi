<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Page;
use Inertia\Response;

class RegulationController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Regulation', [
            'page' => Page::intro('regulation'),
            'features' => Feature::groupedForPage('regulation'),
        ]);
    }
}
