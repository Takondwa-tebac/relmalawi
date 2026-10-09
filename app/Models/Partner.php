<?php

namespace App\Models;

use App\Enums\PartnerType;
use Database\Factories\PartnerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * A radio or television partner shown on the public Partnerships page.
 *
 * @property int $id
 * @property string $name
 * @property PartnerType $type
 * @property string|null $website_url
 * @property int $sort_order
 * @property bool $is_published
 */
#[Fillable(['name', 'type', 'website_url', 'sort_order', 'is_published'])]
class Partner extends Model implements HasMedia
{
    /** @use HasFactory<PartnerFactory> */
    use HasFactory, InteractsWithMedia;

    protected function casts(): array
    {
        return [
            'type' => PartnerType::class,
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @param  Builder<Partner>  $query
     * @return Builder<Partner>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * @param  Builder<Partner>  $query
     * @return Builder<Partner>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Radio and television partners (everything except mobile money).
     *
     * @param  Builder<Partner>  $query
     * @return Builder<Partner>
     */
    public function scopeMedia(Builder $query): Builder
    {
        return $query->where('type', '!=', PartnerType::MobileMoney->value);
    }

    /**
     * Mobile-money (payments) partners only.
     *
     * @param  Builder<Partner>  $query
     * @return Builder<Partner>
     */
    public function scopeMobileMoney(Builder $query): Builder
    {
        return $query->where('type', PartnerType::MobileMoney->value);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
    }
}
