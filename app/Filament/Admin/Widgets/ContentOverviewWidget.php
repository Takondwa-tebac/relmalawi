<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Campaigns\CampaignResource;
use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Admin\Resources\Partners\PartnerResource;
use App\Filament\Admin\Resources\TeamMembers\TeamMemberResource;
use App\Models\Campaign;
use App\Models\ContactMessage;
use App\Models\Partner;
use App\Models\TeamMember;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Headline numbers for what is live on the public site. Each card only shows
 * for users who may view that resource.
 */
class ContentOverviewWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Content overview';

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $stats = [];

        if (CampaignResource::canViewAny()) {
            $live = Campaign::query()->published()->count();
            $total = Campaign::query()->count();

            $stats[] = Stat::make('Campaigns live', $live)
                ->description($total - $live > 0 ? ($total - $live).' unpublished' : 'All published')
                ->descriptionIcon(Heroicon::OutlinedPhoto)
                ->color($live > 0 ? 'success' : 'warning')
                ->url(CampaignResource::getUrl('index'));
        }

        if (TeamMemberResource::canViewAny()) {
            $stats[] = Stat::make('Team members', TeamMember::query()->published()->count())
                ->description('Shown on the People page')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('primary')
                ->url(TeamMemberResource::getUrl('index'));
        }

        if (PartnerResource::canViewAny()) {
            $stats[] = Stat::make('Media partners', Partner::query()->published()->count())
                ->description('Shown on the Partnerships page')
                ->descriptionIcon(Heroicon::OutlinedBuildingOffice2)
                ->color('primary')
                ->url(PartnerResource::getUrl('index'));
        }

        if (ContactMessageResource::canViewAny()) {
            $unread = ContactMessage::query()->unread()->count();
            $week = ContactMessage::query()->where('created_at', '>=', now()->subDays(7))->count();

            $stats[] = Stat::make('Unread messages', $unread)
                ->description($week.' received in the last 7 days')
                ->descriptionIcon(Heroicon::OutlinedEnvelope)
                ->color($unread > 0 ? 'warning' : 'success')
                ->chart($this->messagesPerDay(7))
                ->url(ContactMessageResource::getUrl('index'));
        }

        return $stats;
    }

    public static function canView(): bool
    {
        return CampaignResource::canViewAny()
            || TeamMemberResource::canViewAny()
            || PartnerResource::canViewAny()
            || ContactMessageResource::canViewAny();
    }

    /**
     * Message counts for the last $days days, oldest first, for the sparkline.
     *
     * @return list<int>
     */
    private function messagesPerDay(int $days): array
    {
        $counts = ContactMessage::query()
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->get(['created_at'])
            ->countBy(fn (ContactMessage $message) => $message->created_at->toDateString());

        return collect(range($days - 1, 0))
            ->map(fn (int $ago) => (int) ($counts[now()->subDays($ago)->toDateString()] ?? 0))
            ->all();
    }
}
