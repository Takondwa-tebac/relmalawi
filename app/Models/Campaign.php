<?php

namespace App\Models;

use Database\Factories\CampaignFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * A showcased media campaign (image strip on the home page).
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $alt_text
 * @property string|null $description
 * @property string|null $link_url
 * @property int $sort_order
 * @property bool $is_published
 */
#[Fillable(['title', 'slug', 'alt_text', 'description', 'link_url', 'sort_order', 'is_published'])]
class Campaign extends Model implements HasMedia
{
    /** @use HasFactory<CampaignFactory> */
    use HasFactory, InteractsWithMedia;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 640, 480)
            ->performOnCollections('image')
            ->nonQueued();
    }

    /**
     * @param  Builder<Campaign>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * @param  Builder<Campaign>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }
}
