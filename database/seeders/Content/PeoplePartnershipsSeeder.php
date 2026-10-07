<?php

namespace Database\Seeders\Content;

use App\Enums\PartnerType;
use App\Models\Page;
use App\Models\Partner;
use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\HasMedia;

class PeoplePartnershipsSeeder extends Seeder
{
    /** Team photos ported from the original Next.js app, kept in the repo so seeding is self-contained. */
    private const PHOTOS = __DIR__.'/assets/team';

    public function run(): void
    {
        $this->seedPages();
        $this->seedTeam();
        $this->seedPartners();
    }

    private function seedPages(): void
    {
        Page::query()->updateOrCreate(['slug' => 'people'], [
            'eyebrow' => 'The people behind the work',
            'title' => 'A team with',
            'title_accent' => 'skin in the game.',
            'description' => 'REL is powered by people who understand media, technology, audiences and the responsibility that comes with operating games in public spaces. We bring different strengths to one shared standard: make it clear, make it fair, make it memorable.',
        ]);

        Page::query()->updateOrCreate(['slug' => 'partnerships'], [
            'eyebrow' => 'Our network',
            'title' => 'Media works',
            'title_accent' => 'better together.',
            'description' => "REL does not own radio stations or television channels. We partner with Malawi's media houses, stations and talent to operate engaging, responsible media games where audiences already are.",
        ]);
    }

    private function seedTeam(): void
    {
        $team = [
            ['Martha Chirwa', 'Operations & Station Partnerships', 'Keeps partner relationships moving from first conversation to live execution.', 'team-operations.png'],
            ['Lloyd Banda', 'Commercial Partnerships', 'Builds the bridge between media audiences, brands and sustainable value.', 'team-partnerships.png'],
            ['Thoko Mbewe', 'Product & Technology', 'Shapes the digital tools that make every draw transparent, accessible and easy to participate in.', 'team-product.png'],
            ['Patrick Manda', 'Community & Audience', 'Listens to the people behind the numbers and turns insight into better media games.', 'team-community.png'],
        ];

        foreach ($team as $index => [$name, $role, $bio, $photo]) {
            $member = TeamMember::query()->updateOrCreate(
                ['name' => $name],
                ['role' => $role, 'bio' => $bio, 'sort_order' => $index + 1, 'is_published' => true],
            );

            $this->attachOnce($member, 'photo', self::PHOTOS.'/'.$photo);
        }
    }

    private function seedPartners(): void
    {
        $partners = [
            ['MBC TV 1', PartnerType::Television, 'mbc_tv1_logo.png'],
            ['MBC Radio 2', PartnerType::Radio, 'radio2_logo.png'],
            ['MBC TV 2', PartnerType::Television, 'mbc2_tv_logo.png'],
            ['Zodiak', PartnerType::RadioAndTelevision, 'zodiak_logo.png'],
            ['Times 360', PartnerType::RadioAndTelevision, 'times_logo.png'],
            ['Timveni', PartnerType::RadioAndTelevision, 'timveni_logo.png'],
            ['Angaliba TV/FM', PartnerType::RadioAndTelevision, 'angaliba_logo.png'],
        ];

        // Partners dropped because no confident logo could be sourced; removed from existing databases.
        Partner::query()->whereIn('name', ['Jojo FM', 'Mibawa TV', 'Ntchisi Youth FM'])->get()->each->delete();

        foreach ($partners as $index => [$name, $type, $logo]) {
            $partner = Partner::query()->updateOrCreate(
                ['name' => $name],
                ['type' => $type, 'sort_order' => $index + 1, 'is_published' => true],
            );

            if ($logo !== null) {
                $this->attachOnce($partner, 'logo', public_path('images/partners/'.$logo));
            }
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
