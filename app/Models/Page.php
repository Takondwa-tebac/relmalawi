<?php

namespace App\Models;

use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Editable intro copy and visibility for a public page.
 *
 * @property int $id
 * @property string $slug
 * @property bool $is_active
 * @property bool $show_in_nav
 * @property string|null $nav_label
 * @property int $nav_sort
 * @property string|null $eyebrow
 * @property string $title
 * @property string|null $title_accent
 * @property string|null $description
 * @property string|null $meta_title
 * @property string|null $meta_description
 */
#[Fillable([
    'slug', 'is_active', 'show_in_nav', 'nav_label', 'nav_sort',
    'eyebrow', 'title', 'title_accent', 'description', 'meta_title', 'meta_description',
])]
class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    private const VISIBILITY_CACHE_KEY = 'page-visibility';

    /**
     * Slugs of every public page, in navigation order.
     *
     * @var list<string>
     */
    public const SLUGS = [
        'home', 'about', 'how-it-works', 'raffles', 'partnerships',
        'technology', 'people', 'regulation', 'faq', 'contact',
    ];

    /**
     * Pages that can appear as navbar tabs, with their default labels, in default order.
     * Home (the logo) and Contact (the "Connect" button) are never navbar tabs.
     *
     * @var array<string, string>
     */
    public const NAV_LABELS = [
        'about' => 'About',
        'how-it-works' => 'How it works',
        'raffles' => 'Our raffles',
        'partnerships' => 'Partnerships',
        'technology' => 'Technology',
        'people' => 'People',
        'regulation' => 'Regulation',
        'faq' => 'FAQ',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'show_in_nav' => 'boolean',
            'nav_sort' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        // The home page is the site; it can never be switched off.
        static::saving(function (Page $page) {
            if ($page->slug === 'home') {
                $page->is_active = true;
            }
        });

        static::saved(fn () => Cache::forget(self::VISIBILITY_CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::VISIBILITY_CACHE_KEY));
    }

    public static function pathFor(string $slug): string
    {
        return $slug === 'home' ? '/' : '/'.$slug;
    }

    /**
     * Visibility of every public page, keyed by slug. A page with no row yet counts as
     * active and shown in the navbar, so a fresh install works before anything is seeded.
     *
     * @return array<string, array{active: bool, show_in_nav: bool, label: string, sort: int}>
     */
    public static function visibility(): array
    {
        return Cache::rememberForever(self::VISIBILITY_CACHE_KEY, function () {
            $rows = static::query()
                ->get(['slug', 'is_active', 'show_in_nav', 'nav_label', 'nav_sort'])
                ->keyBy('slug');

            $visibility = [];

            foreach (self::SLUGS as $slug) {
                $row = $rows->get($slug);

                $visibility[$slug] = [
                    'active' => $slug === 'home' ? true : ($row?->is_active ?? true),
                    'show_in_nav' => $row?->show_in_nav ?? true,
                    'label' => filled($row?->nav_label) ? $row->nav_label : (self::NAV_LABELS[$slug] ?? ucfirst($slug)),
                    'sort' => (int) ($row?->nav_sort ?? 0),
                ];
            }

            return $visibility;
        });
    }

    public static function isActive(string $slug): bool
    {
        return self::visibility()[$slug]['active'] ?? true;
    }

    /**
     * Navbar tabs: active pages that are set to show, ordered by nav_sort (when set), then the default order.
     *
     * @return list<array{slug: string, label: string, href: string}>
     */
    public static function navigation(): array
    {
        $visibility = self::visibility();
        $position = array_flip(array_keys(self::NAV_LABELS));

        return collect(array_keys(self::NAV_LABELS))
            ->filter(fn (string $slug) => $visibility[$slug]['active'] && $visibility[$slug]['show_in_nav'])
            ->sortBy(fn (string $slug) => [$visibility[$slug]['sort'] ?: PHP_INT_MAX, $position[$slug]])
            ->map(fn (string $slug) => [
                'slug' => $slug,
                'label' => $visibility[$slug]['label'],
                'href' => self::pathFor($slug),
            ])
            ->values()
            ->all();
    }

    /**
     * Every active public path, e.g. ['/', '/about'].
     *
     * @return list<string>
     */
    public static function activePaths(): array
    {
        return collect(self::visibility())
            ->filter(fn (array $page) => $page['active'])
            ->keys()
            ->map(fn (string $slug) => self::pathFor($slug))
            ->values()
            ->all();
    }

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
