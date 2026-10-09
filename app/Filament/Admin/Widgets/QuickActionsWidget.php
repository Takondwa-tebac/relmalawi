<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Pages\ManageSiteSettings;
use App\Filament\Admin\Resources\Campaigns\CampaignResource;
use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Admin\Resources\Features\FeatureResource;
use App\Filament\Admin\Resources\Pages\PageResource;
use App\Filament\Admin\Resources\Partners\PartnerResource;
use App\Filament\Admin\Resources\TeamMembers\TeamMemberResource;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;

/**
 * Greets the signed-in user and links to what they are allowed to do.
 */
class QuickActionsWidget extends Widget
{
    protected static ?int $sort = 1;

    // Cheap and at the top of the page, so render it immediately instead of lazy-loading.
    protected static bool $isLazy = false;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.quick-actions';

    public function getName(): string
    {
        return (string) auth()->user()?->name;
    }

    public function getRoleLabel(): string
    {
        return (string) auth()->user()?->getRoleNames()->first();
    }

    /**
     * @return list<array{label: string, url: string, icon: Heroicon, new_tab?: bool}>
     */
    public function getLinks(): array
    {
        $links = [];

        if (CampaignResource::canCreate()) {
            $links[] = ['label' => 'Add campaign', 'url' => CampaignResource::getUrl('create'), 'icon' => Heroicon::OutlinedPhoto];
        }

        if (TeamMemberResource::canCreate()) {
            $links[] = ['label' => 'Add team member', 'url' => TeamMemberResource::getUrl('create'), 'icon' => Heroicon::OutlinedUserPlus];
        }

        if (PartnerResource::canCreate()) {
            $links[] = ['label' => 'Add partner', 'url' => PartnerResource::getUrl('create'), 'icon' => Heroicon::OutlinedBuildingOffice2];
        }

        if (PageResource::canViewAny()) {
            $links[] = ['label' => 'Edit page copy', 'url' => PageResource::getUrl('index'), 'icon' => Heroicon::OutlinedDocumentText];
        }

        if (FeatureResource::canViewAny()) {
            $links[] = ['label' => 'Edit feature cards', 'url' => FeatureResource::getUrl('index'), 'icon' => Heroicon::OutlinedSquares2x2];
        }

        if (ContactMessageResource::canViewAny()) {
            $links[] = ['label' => 'Open inbox', 'url' => ContactMessageResource::getUrl('index'), 'icon' => Heroicon::OutlinedInbox];
        }

        if (ManageSiteSettings::canAccess()) {
            $links[] = ['label' => 'Site settings', 'url' => ManageSiteSettings::getUrl(), 'icon' => Heroicon::OutlinedCog6Tooth];
        }

        $links[] = ['label' => 'View public site', 'url' => url('/'), 'icon' => Heroicon::OutlinedArrowTopRightOnSquare, 'new_tab' => true];

        return $links;
    }
}
