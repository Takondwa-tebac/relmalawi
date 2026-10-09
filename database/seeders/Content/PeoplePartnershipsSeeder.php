<?php

namespace Database\Seeders\Content;

use App\Enums\PartnerType;
use App\Models\Partner;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\HasMedia;

/**
 * Team members and partners. Page and Feature copy for /people and /partnerships
 * lives in LeadershipPartnershipsSeeder.
 */
class PeoplePartnershipsSeeder extends Seeder
{
    /** Leader photos, kept in the repo so seeding is self-contained. */
    private const PHOTOS = __DIR__.'/assets/leaders';

    /** Logos for partners that are not in public/images/partners. */
    private const PARTNER_LOGOS = __DIR__.'/assets/partners';

    /** Fake placeholder people from the original UI mock; removed if they still exist. */
    private const PLACEHOLDERS = ['Martha Chirwa', 'Lloyd Banda', 'Thoko Mbewe', 'Patrick Manda'];

    public function run(): void
    {
        $this->seedTeam();
        $this->seedPartners();
    }

    private function seedTeam(): void
    {
        TeamMember::query()->whereIn('name', self::PLACEHOLDERS)->get()->each->delete();

        $team = [
            [
                'Kobbina Awuah',
                'Co-Founder',
                'Co-founded REL to bring gaming products to a new audience through radio and television.',
                implode("\n", [
                    'Over 10 years of strategic and management experience in Sub-Saharan African markets',
                    'Co-founded Radio Entertainment Limited to bring gaming products to a new demographic and create new revenue streams for media houses',
                    'Co-founded Peak Investment Capital (PIC), an Africa-focused search fund',
                    'Previously Head of Strategy at Tigo Tanzania, leading strategy to drive growth outside Dar es Salaam',
                    'MBA, Harvard Business School',
                    'BSc Mechanical Engineering, Cornell University',
                ]),
                'kobbina-awuah.png',
            ],
            [
                'Ike Kyei',
                'Leadership team',
                'Strategy and operations leader with a consulting background across Africa.',
                implode("\n", [
                    '8+ years of experience in strategy and operations',
                    'Previously a consultant at McKinsey & Company, focusing on financial services, telecoms and the public sector, serving clients across Africa',
                    'Earlier at Ford Motor Company, Dearborn, Michigan, USA (operations and Six Sigma analysis)',
                    'MBA, Harvard Business School',
                    'BSc Chemical Engineering, Massachusetts Institute of Technology',
                ]),
                'ike-kyei.png',
            ],
            [
                'Michael Kampani',
                'Leadership team',
                'Chartered Accountant with senior finance and management roles across Africa.',
                implode("\n", [
                    'Chartered Accountant, CA (Mw) and ACMA/CGMA',
                    'BCom (Accounting), University of Malawi',
                    'Founder and CEO, Atlanto Security Limited (2022 to date)',
                    'Regional Finance Director Africa, GardaWorld (2017-2022)',
                    'Managing Director, G4S Botswana (2013-2017)',
                    'Regional Financial Controller Africa, G4S Africa (2011-2013)',
                    'Finance Director, G4S Botswana (2006-2011)',
                    'Chief Accountant, G4S Malawi (1999-2006)',
                ]),
                'michael-kampani.png',
            ],
        ];

        foreach ($team as $index => [$name, $role, $summary, $bio, $photo]) {
            $member = TeamMember::query()->firstOrNew(['name' => $name]);

            // Only fill a record we are creating or that has never been given a summary,
            // so edits made in the CMS are not overwritten on re-seed.
            if (! $member->exists) {
                $member->fill([
                    'role' => $role,
                    'summary' => $summary,
                    'bio' => $bio,
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ])->save();
            } elseif ($member->summary === null) {
                $member->update(['summary' => $summary]);
            }

            $this->attachOnce($member, 'photo', self::PHOTOS.'/'.$photo);
        }
    }

    private function seedPartners(): void
    {
        $partners = [
            ['MBC TV 1', PartnerType::Television, public_path('images/partners/mbc_tv1_logo.png')],
            ['MBC Radio 2', PartnerType::Radio, public_path('images/partners/radio2_logo.png')],
            ['MBC TV 2', PartnerType::Television, public_path('images/partners/mbc2_tv_logo.png')],
            ['Zodiak', PartnerType::RadioAndTelevision, public_path('images/partners/zodiak_logo.png')],
            ['Times 360', PartnerType::RadioAndTelevision, public_path('images/partners/times_logo.png')],
            ['Timveni', PartnerType::RadioAndTelevision, public_path('images/partners/timveni_logo.png')],
            ['Angaliba TV/FM', PartnerType::RadioAndTelevision, public_path('images/partners/angaliba_logo.png')],
            ['Airtel Money', PartnerType::MobileMoney, self::PARTNER_LOGOS.'/airtel_money.png'],
            ['TNM Mpamba', PartnerType::MobileMoney, self::PARTNER_LOGOS.'/tnm_mpamba.png'],
        ];

        // Partners dropped because no confident logo could be sourced; removed from existing databases.
        Partner::query()->whereIn('name', ['Jojo FM', 'Mibawa TV', 'Ntchisi Youth FM'])->get()->each->delete();

        foreach ($partners as $index => [$name, $type, $logo]) {
            $partner = Partner::query()->firstOrNew(['name' => $name]);

            if (! $partner->exists) {
                $partner->fill(['type' => $type, 'sort_order' => $index + 1, 'is_published' => true])->save();
            }

            $this->attachOnce($partner, 'logo', $logo);
        }
    }

    /**
     * Copy a source image into a single-file collection unless it already has one.
     */
    private function attachOnce(HasMedia $model, string $collection, string $path): void
    {
        if ($model->hasMedia($collection) || ! is_file($path)) {
            return;
        }

        $model->addMedia($path)->preservingOriginal()->toMediaCollection($collection);
    }
}
