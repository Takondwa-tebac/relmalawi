<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\HowItWorksStep;
use App\Models\Page;
use Inertia\Response;

class HowItWorksController extends Controller
{
    public function __invoke(): Response
    {
        $steps = HowItWorksStep::query()
            ->published()
            ->ordered()
            ->get(['id', 'title', 'body', 'icon'])
            ->values()
            ->map(fn (HowItWorksStep $step, int $index) => [
                'id' => $step->id,
                'number' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                'title' => $step->title,
                'body' => $step->body,
                'icon' => $step->icon,
            ]);

        return inertia('HowItWorks', [
            'page' => Page::intro('how-it-works'),
            'steps' => $steps,
        ]);
    }
}
