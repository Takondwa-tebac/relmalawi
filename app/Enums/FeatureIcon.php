<?php

namespace App\Enums;

/**
 * Allowed icon keys for a Feature. The Vue registry
 * (resources/js/components/guest/FeatureIcon.vue) must map every case below.
 */
enum FeatureIcon: string
{
    case Radio = 'radio';
    case Gamepad = 'gamepad';
    case ShieldCheck = 'shield-check';
    case Shuffle = 'shuffle';
    case BarChart = 'bar-chart';
    case Lock = 'lock';
    case Eye = 'eye';
    case Crown = 'crown';
    case Gift = 'gift';
    case FileCheck = 'file-check';
    case Scale = 'scale';
    case CheckCircle = 'check-circle';

    public function label(): string
    {
        return match ($this) {
            self::Radio => 'Radio',
            self::Gamepad => 'Gamepad',
            self::ShieldCheck => 'Shield with check',
            self::Shuffle => 'Shuffle',
            self::BarChart => 'Bar chart',
            self::Lock => 'Lock',
            self::Eye => 'Eye',
            self::Crown => 'Crown',
            self::Gift => 'Gift',
            self::FileCheck => 'File with check',
            self::Scale => 'Scale',
            self::CheckCircle => 'Check circle',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $icon) => [$icon->value => $icon->label()])
            ->all();
    }
}
