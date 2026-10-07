<?php

namespace App\Http\Resources;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Campaign
 */
class CampaignResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $media = $this->getFirstMedia('image');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'alt' => $this->alt_text,
            'description' => $this->description,
            'link_url' => $this->link_url,
            'image' => $media?->getUrl(),
            'thumb' => $media?->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : $media?->getUrl(),
        ];
    }
}
