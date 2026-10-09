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
    case AboutStory = 'about.story';
    case AboutServicesIntro = 'about.servicesIntro';
    case AboutServices = 'about.services';
    case AboutStepsIntro = 'about.stepsIntro';
    case AboutSteps = 'about.steps';
    case AboutTeam = 'about.team';
    case TechnologyRows = 'technology.rows';
    case TechnologyDashboardsIntro = 'technology.dashboardsIntro';
    case TechnologyDashboards = 'technology.dashboards';
    case TechnologyReportsIntro = 'technology.reportsIntro';
    case TechnologyReports = 'technology.reports';
    case TechnologyUssd = 'technology.ussd';
    case TechnologyRunsIntro = 'technology.runsIntro';
    case TechnologyRuns = 'technology.runs';
    case TechnologyCta = 'technology.cta';
    case RegulationRows = 'regulation.rows';
    case RegulationPromisesIntro = 'regulation.promisesIntro';
    case RegulationPromises = 'regulation.promises';
    case RegulationCta = 'regulation.cta';
    case HomeIntro = 'home.intro';
    case HomeStats = 'home.stats';
    case HomeStepsHeading = 'home.steps_heading';
    case HomeSteps = 'home.steps';
    case HomeAudiences = 'home.audiences';
    case HomeTransparency = 'home.transparency';
    case HomeTransparencyItems = 'home.transparency_items';
    case HomePayments = 'home.payments';
    case HomeFaq = 'home.faq';
    case HomeCta = 'home.cta';
    case PeoplePrinciples = 'people.principles';
    case PartnershipsOffers = 'partnerships.offers';
    case PartnershipsGet = 'partnerships.get';
    case PartnershipsAsk = 'partnerships.ask';
    case PartnershipsEarn = 'partnerships.earn';
    case PartnershipsRoles = 'partnerships.roles';
    case PartnershipsSteps = 'partnerships.steps';
    case PartnershipsCta = 'partnerships.cta';
    case PartnershipsStepsIntro = 'partnerships.stepsIntro';
    case PartnershipsRolesIntro = 'partnerships.rolesIntro';
    case PartnershipsAskIntro = 'partnerships.askIntro';
    case PartnershipsGetIntro = 'partnerships.getIntro';
    case PartnershipsPaymentsIntro = 'partnerships.paymentsIntro';
    case PartnershipsMediaIntro = 'partnerships.mediaIntro';
    case PartnershipsHero = 'partnerships.hero';
    case PeopleJoin = 'people.join';
    case PeopleBrings = 'people.brings';
    case PeopleBringsIntro = 'people.bringsIntro';
    case PeoplePrinciplesIntro = 'people.principlesIntro';
    case RafflesCampaign = 'raffles.campaign';
    case RafflesChannelsIntro = 'raffles.channelsIntro';
    case RafflesChannels = 'raffles.channels';
    case RafflesCta = 'raffles.cta';
    case HowItWorksHero = 'how-it-works.hero';
    case HowItWorksUssdIntro = 'how-it-works.ussdIntro';
    case HowItWorksUssd = 'how-it-works.ussd';
    case HowItWorksBehind = 'how-it-works.behind';
    case HowItWorksRolesIntro = 'how-it-works.rolesIntro';
    case HowItWorksRoles = 'how-it-works.roles';
    case HowItWorksFaqIntro = 'how-it-works.faqIntro';
    case HowItWorksCta = 'how-it-works.cta';

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
            self::RafflesBand => 'Raffles: Managed end to end band',
            self::RafflesCards => 'Raffles: Legacy cards (unused)',
            self::TechnologyCapabilities => 'Technology: Capabilities',
            self::TechnologyAccountability => 'Technology: Accountability block',
            self::RegulationCommitments => 'Regulation: Commitments',
            self::RegulationStandard => 'Regulation: Our standard block',
            self::AboutStory => 'About: Our story row',
            self::AboutServicesIntro => 'About: What we do heading',
            self::AboutServices => 'About: What we do cards',
            self::AboutStepsIntro => 'About: Partnership steps heading',
            self::AboutSteps => 'About: Partnership steps',
            self::AboutTeam => 'About: Leadership strip heading',
            self::TechnologyRows => 'Technology: Feature rows (draws, payments)',
            self::TechnologyDashboardsIntro => 'Technology: Dashboards heading',
            self::TechnologyDashboards => 'Technology: Dashboards by role',
            self::TechnologyReportsIntro => 'Technology: Reports heading',
            self::TechnologyReports => 'Technology: Real-time reports ticks',
            self::TechnologyUssd => 'Technology: USSD row',
            self::TechnologyRunsIntro => 'Technology: What REL runs heading',
            self::TechnologyRuns => 'Technology: What REL runs list',
            self::TechnologyCta => 'Technology: Call-to-action band',
            self::RegulationRows => 'Regulation: Feature rows',
            self::RegulationPromisesIntro => 'Regulation: What REL commits to heading',
            self::RegulationPromises => 'Regulation: What REL commits to list',
            self::RegulationCta => 'Regulation: Call-to-action band',
            self::HomeIntro => 'Home: Intro section',
            self::HomeStats => 'Home: Headline stats band',
            self::HomeStepsHeading => 'Home: How playing works heading',
            self::HomeSteps => 'Home: How playing works steps',
            self::HomeAudiences => 'Home: Audience cards',
            self::HomeTransparency => 'Home: Transparency band heading',
            self::HomeTransparencyItems => 'Home: Transparency band list',
            self::HomePayments => 'Home: Payments row heading',
            self::HomeFaq => 'Home: FAQ teaser heading',
            self::HomeCta => 'Home: Closing call-to-action band',
            self::PeoplePrinciples => 'People: How we work blocks',
            self::PartnershipsOffers => 'Partnerships: Intro cards',
            self::PartnershipsGet => 'Partnerships: What you get with REL',
            self::PartnershipsAsk => 'Partnerships: What we ask of media partners',
            self::PartnershipsEarn => 'Partnerships: How partners earn band',
            self::PartnershipsRoles => 'Partnerships: Dashboard for every role',
            self::PartnershipsSteps => 'Partnerships: Getting started steps',
            self::PartnershipsCta => 'Partnerships: Closing call-to-action band',
            self::PartnershipsStepsIntro => 'Partnerships: Getting started heading',
            self::PartnershipsRolesIntro => 'Partnerships: Dashboards heading',
            self::PartnershipsAskIntro => 'Partnerships: What we ask heading',
            self::PartnershipsGetIntro => 'Partnerships: What you get heading',
            self::PartnershipsPaymentsIntro => 'Partnerships: Payments partners heading',
            self::PartnershipsMediaIntro => 'Partnerships: Media partners heading',
            self::PartnershipsHero => 'Partnerships: Intro buttons',
            self::PeopleJoin => 'People: Join the network band',
            self::PeopleBrings => 'People: What the team brings strip',
            self::PeopleBringsIntro => 'People: What the team brings heading',
            self::PeoplePrinciplesIntro => 'People: How we work heading',
            self::RafflesCampaign => 'Raffles: Branded campaigns row',
            self::RafflesChannelsIntro => 'Raffles: How stations promote it heading',
            self::RafflesChannels => 'Raffles: Promotion channels (numbered grid)',
            self::RafflesCta => 'Raffles: Closing call-to-action band',
            self::HowItWorksHero => 'How it works: Hero buttons',
            self::HowItWorksUssdIntro => 'How it works: Phone mock-up heading',
            self::HowItWorksUssd => 'How it works: Phone mock-up screens (one row per screen)',
            self::HowItWorksBehind => 'How it works: Behind the scenes rows',
            self::HowItWorksRolesIntro => 'How it works: Dashboards by role heading',
            self::HowItWorksRoles => 'How it works: Dashboards by role cards',
            self::HowItWorksFaqIntro => 'How it works: Good to know heading',
            self::HowItWorksCta => 'How it works: Closing call-to-action band',
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
