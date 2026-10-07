<?php

namespace App\Enums;

/**
 * Known Feature groups. Each group belongs to exactly one page.
 * Other pages may add their own groups as free strings; this enum drives the CMS select.
 */
enum FeatureGroup: string
{
    case AboutRole = 'about.role';
    case AboutStats = 'about.stats';
    case AboutGuides = 'about.guides';
    case AboutPillars = 'about.pillars';
    case AboutCta = 'about.cta';
    case RafflesFormats = 'raffles.formats';
    case RafflesBand = 'raffles.band';
    case RafflesCards = 'raffles.cards';
    case TechnologyCapabilities = 'technology.capabilities';
    case TechnologyAccountability = 'technology.accountability';
    case RegulationCommitments = 'regulation.commitments';
    case RegulationStandard = 'regulation.standard';

    public function pageSlug(): string
    {
        return explode('.', $this->value)[0];
    }

    public function label(): string
    {
        return match ($this) {
            self::AboutRole => 'About: Our role panel',
            self::AboutStats => 'About: Stat boxes',
            self::AboutGuides => 'About: Pillars heading',
            self::AboutPillars => 'About: Guiding pillars',
            self::AboutCta => 'About: Call-to-action band',
            self::RafflesFormats => 'Raffles: Draw formats',
            self::RafflesBand => 'Raffles: Core formats band',
            self::RafflesCards => 'Raffles: Core formats cards',
            self::TechnologyCapabilities => 'Technology: Capabilities',
            self::TechnologyAccountability => 'Technology: Accountability block',
            self::RegulationCommitments => 'Regulation: Commitments',
            self::RegulationStandard => 'Regulation: Our standard block',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $group) => [$group->value => $group->label()])
            ->all();
    }
}
