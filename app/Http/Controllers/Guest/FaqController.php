<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;
use Inertia\Response;

class FaqController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Faq', [
            'page' => Page::intro('faq'),
            'faqs' => Faq::query()->published()->ordered()->get(['id', 'category', 'question', 'answer']),
        ]);
    }
}
