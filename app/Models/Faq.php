<?php

namespace App\Models;

use Database\Factories\FaqFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A frequently asked question, shown on /faq and (when flagged) on the home page.
 *
 * @property int $id
 * @property string $category
 * @property string $question
 * @property string $answer
 * @property int $sort_order
 * @property bool $is_published
 * @property bool $show_on_home
 */
#[Fillable(['category', 'question', 'answer', 'sort_order', 'is_published', 'show_on_home'])]
class Faq extends Model
{
    /** @use HasFactory<FaqFactory> */
    use HasFactory;

    /**
     * Categories offered in the CMS and used to group the public FAQ page.
     *
     * @var list<string>
     */
    public const CATEGORIES = ['Playing', 'Payments & prizes', 'Fairness & regulation', 'Media partners'];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'show_on_home' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @param  Builder<Faq>  $query
     * @return Builder<Faq>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * @param  Builder<Faq>  $query
     * @return Builder<Faq>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @param  Builder<Faq>  $query
     * @return Builder<Faq>
     */
    public function scopeForHome(Builder $query): Builder
    {
        return $query->where('show_on_home', true);
    }
}
