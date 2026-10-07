<?php

namespace App\Http\Resources;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Feature
 */
class FeatureResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group' => $this->group,
            'eyebrow' => $this->eyebrow,
            'title' => $this->title,
            'body' => $this->body,
            'icon' => $this->icon?->value,
            'meta' => $this->meta ?? [],
        ];
    }
}
