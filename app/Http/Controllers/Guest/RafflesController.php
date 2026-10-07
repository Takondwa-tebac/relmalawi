<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Page;
use Inertia\Response;

class RafflesController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Raffles', [
            'page' => Page::intro('raffles'),
            'features' => Feature::groupedForPage('raffles'),
        ]);
    }
}
