<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Widgets\ChartWidget;

class ContactMessagesChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Contact messages';

    protected ?string $description = 'Messages received per day';

    protected ?string $maxHeight = '260px';

    public ?string $filter = '30';

    public static function canView(): bool
    {
        return ContactMessageResource::canViewAny();
    }

    protected function getFilters(): ?array
    {
        return [
            '7' => 'Last 7 days',
            '30' => 'Last 30 days',
            '90' => 'Last 90 days',
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $days = (int) $this->filter;

        $counts = ContactMessage::query()
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->get(['created_at'])
            ->countBy(fn (ContactMessage $message) => $message->created_at->toDateString());

        $dates = collect(range($days - 1, 0))->map(fn (int $ago) => now()->subDays($ago));

        return [
            'datasets' => [
                [
                    'label' => 'Messages',
                    'data' => $dates->map(fn ($date) => (int) ($counts[$date->toDateString()] ?? 0))->all(),
                    'borderColor' => '#e4bc19',
                    'backgroundColor' => 'rgba(228, 188, 25, 0.15)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $dates->map(fn ($date) => $date->format('j M'))->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'plugins' => ['legend' => ['display' => false]],
            'scales' => ['y' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]],
        ];
    }
}
