<?php

namespace App\Models;

use App\Enums\FeatureIcon;
use App\Http\Resources\FeatureResource;
use Database\Factories\FeatureFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * An ordered, grouped content card/block for a public page.
 *
 * Query it with Feature::groupedForPage('about'): published rows, ordered,
 * keyed by group name without the page prefix (['pillars' => [item, ...], ...]);
 * a group not prefixed with the slug keeps its full name. Ready for an Inertia prop.
 *
 * @property int $id
 * @property string $page_slug
 * @property string $group
 * @property string|null $eyebrow
 * @property string $title
 * @property string|null $body
 * @property FeatureIcon|null $icon
 * @property array<string, mixed>|null $meta
 * @property int $sort_order
 * @property bool $is_published
 */
#[Fillable(['page_slug', 'group', 'eyebrow', 'title', 'body', 'icon', 'meta', 'sort_order', 'is_published'])]
class Feature extends Model
{
    /** @use HasFactory<FeatureFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'icon' => FeatureIcon::class,
            'meta' => 'array',
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Published features of one page, in display order.
     *
     * @param  Builder<Feature>  $query
     */
    public function scopeForPageSlug(Builder $query, string $slug): void
    {
        $query->where('page_slug', $slug)
            ->where('is_published', true)
            ->orderBy('group')
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    /**
     * One query; grouped in PHP.
     *
     * @return array<string, list<array<string, mixed>>>
     */
    public static function groupedForPage(string $slug): array
    {
        return static::query()
            ->forPageSlug($slug)
            ->get()
            ->groupBy(fn (self $feature) => Str::after($feature->group, $slug.'.'))
            ->map(fn ($items) => FeatureResource::collection($items)->resolve())
            ->all();
    }
}
