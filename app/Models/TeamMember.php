<?php

namespace App\Models;

use Database\Factories\TeamMemberFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * A person shown on the public People page.
 *
 * @property int $id
 * @property string $name
 * @property string $role
 * @property string|null $summary
 * @property string|null $bio
 * @property int $sort_order
 * @property bool $is_published
 */
#[Fillable(['name', 'role', 'summary', 'bio', 'sort_order', 'is_published'])]
class TeamMember extends Model implements HasMedia
{
    /** @use HasFactory<TeamMemberFactory> */
    use HasFactory, InteractsWithMedia;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @param  Builder<TeamMember>  $query
     * @return Builder<TeamMember>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * @param  Builder<TeamMember>  $query
     * @return Builder<TeamMember>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('card')
            ->performOnCollections('photo')
            ->fit(Fit::Crop, 640, 800)
            ->nonQueued();
    }
}
