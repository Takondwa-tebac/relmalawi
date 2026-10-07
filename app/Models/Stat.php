<?php

namespace App\Models;

use Database\Factories\StatFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A headline figure shown on the home hero (e.g. "40" / "Dynamic codes").
 *
 * @property int $id
 * @property string $value
 * @property string $label
 * @property int $sort_order
 */
#[Fillable(['value', 'label', 'sort_order'])]
class Stat extends Model
{
    /** @use HasFactory<StatFactory> */
    use HasFactory;
}
