<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Campaigns\CampaignResource;
use App\Filament\Admin\Resources\Partners\PartnerResource;
use App\Filament\Admin\Resources\TeamMembers\TeamMemberResource;
use App\Models\Campaign;
use App\Models\Partner;
use App\Models\TeamMember;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

/**
 * Content health: published items that are missing their image and would look
 * broken or empty on the public site.
 */
class NeedsAttentionWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Needs attention';

    protected ?string $description = 'Published items that are missing an image';

    protected function getStats(): array
    {
        $stats = [];

        if (CampaignResource::canViewAny()) {
            $stats[] = $this->healthStat(
                'Campaigns without an image',
                Campaign::query()->published()->whereDoesntHave('media', fn (Builder $q) => $q->where('collection_name', 'image'))->count(),
                CampaignResource::getUrl('index'),
            );
        }

        if (TeamMemberResource::canViewAny()) {
            $stats[] = $this->healthStat(
                'Team members without a photo',
                TeamMember::query()->published()->whereDoesntHave('media', fn (Builder $q) => $q->where('collection_name', 'photo'))->count(),
                TeamMemberResource::getUrl('index'),
            );
        }

        if (PartnerResource::canViewAny()) {
            $stats[] = $this->healthStat(
                'Partners without a logo',
                Partner::query()->published()->whereDoesntHave('media', fn (Builder $q) => $q->where('collection_name', 'logo'))->count(),
                PartnerResource::getUrl('index'),
            );
        }

        return $stats;
    }

    public static function canView(): bool
    {
        return CampaignResource::canViewAny()
            || TeamMemberResource::canViewAny()
            || PartnerResource::canViewAny();
    }

    private function healthStat(string $label, int $missing, string $url): Stat
    {
        return Stat::make($label, $missing)
            ->description($missing === 0 ? 'All good' : 'Upload the missing image')
            ->descriptionIcon($missing === 0 ? Heroicon::OutlinedCheckCircle : Heroicon::OutlinedExclamationTriangle)
            ->color($missing === 0 ? 'success' : 'warning')
            ->url($url);
    }
}
