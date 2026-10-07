<?php

namespace App\Http\Resources;

use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Partner
 */
class PartnerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type->value,
            'website_url' => $this->website_url,
            'logo_url' => $this->getFirstMediaUrl('logo') ?: null,
        ];
    }
}
