<?php

namespace App\Models;

use Database\Factories\HowItWorksStepFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * One numbered step on the How it works page. The number is derived from sort_order.
 *
 * @property int $id
 * @property string $title
 * @property string|null $body
 * @property string|null $icon
 * @property int $sort_order
 * @property bool $is_published
 */
#[Fillable(['title', 'body', 'icon', 'sort_order', 'is_published'])]
class HowItWorksStep extends Model
{
    /** @use HasFactory<HowItWorksStepFactory> */
    use HasFactory;

    /**
     * Icons the Vue page knows how to render.
     *
     * @var array<string, string>
     */
    public const ICONS = [
        'smartphone' => 'Phone / shortcode',
        'wallet' => 'Wallet / payment',
        'message-square' => 'SMS / message',
        'shuffle' => 'Draw',
        'trophy' => 'Trophy / winners',
        'banknote' => 'Payout',
        'radio' => 'Radio / station',
        'check-circle' => 'Check',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @param  Builder<HowItWorksStep>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * @param  Builder<HowItWorksStep>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order')->orderBy('id');
    }
}
