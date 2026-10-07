<?php

namespace App\Models;

use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Editable intro copy for a public page (eyebrow, two-tone headline, description).
 *
 * @property int $id
 * @property string $slug
 * @property string|null $eyebrow
 * @property string $title
 * @property string|null $title_accent
 * @property string|null $description
 * @property string|null $meta_title
 * @property string|null $meta_description
 */
#[Fillable(['slug', 'eyebrow', 'title', 'title_accent', 'description', 'meta_title', 'meta_description'])]
class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    /**
     * Slugs of every public page, in navigation order.
     *
     * @var list<string>
     */
    public const SLUGS = [
        'home', 'about', 'how-it-works', 'raffles', 'partnerships',
        'technology', 'people', 'regulation', 'contact',
    ];

    /**
     * Props for the Vue <PageIntro>; falls back to an empty shell if not seeded.
     *
     * @return array<string, string|null>
     */
    public static function intro(string $slug): array
    {
        $page = static::query()->where('slug', $slug)->first();

        return [
            'slug' => $slug,
            'eyebrow' => $page?->eyebrow,
            'title' => $page?->title ?? '',
            'title_accent' => $page?->title_accent,
            'description' => $page?->description,
            'meta_title' => $page?->meta_title ?? $page?->title,
            'meta_description' => $page?->meta_description,
        ];
    }
}
