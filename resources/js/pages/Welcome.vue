<script setup lang="ts">
import { ArrowUpRightIcon } from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import CampaignShowcase from '@/components/guest/CampaignShowCase.vue';
import StatCard from '@/components/guest/StatsCard.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { CampaignData, HomeHero, StatData } from '@/types/home';

defineOptions({ layout: GuestLayout });

defineProps<{
    page: PageIntroData;
    hero: HomeHero;
    campaigns: CampaignData[];
    stats: StatData[];
}>();
</script>

<template>
    <Head :title="page.meta_title ?? page.title">
        <meta
            v-if="page.meta_description"
            head-key="description"
            name="description"
            :content="page.meta_description"
        />
    </Head>

    <section class="bg-[#073b2a]">
        <div
            class="mx-auto grid min-h-[calc(100vh-220px)] w-full max-w-7xl items-center gap-12 px-6 py-16 sm:px-10 lg:grid-cols-[1.05fr_.95fr] lg:px-14"
        >
            <div>
                <div
                    v-if="page.eyebrow"
                    class="mb-8 flex items-center gap-3 text-xs font-bold tracking-[0.25em] text-[#e4bc19] uppercase"
                >
                    <span class="h-1 w-10 bg-[#e4bc19]" />
                    {{ page.eyebrow }}
                </div>
                <h1
                    class="max-w-3xl text-[clamp(4rem,10vw,8.5rem)] leading-[0.82] font-black tracking-[-0.08em] text-[#f7edcf] uppercase"
                >
                    {{ page.title }}
                    <span v-if="page.title_accent" class="block text-[#e4bc19]">
                        {{ page.title_accent }}
                    </span>
                    <span v-if="hero.title_tail" class="block">
                        {{ hero.title_tail }}
                    </span>
                </h1>
                <p
                    v-if="page.description"
                    class="mt-9 max-w-xl text-lg leading-8 text-[#f7edcf]/70"
                >
                    {{ page.description }}
                </p>
                <p
                    v-if="hero.secondary"
                    class="mt-4 max-w-xl text-base leading-7 text-[#f7edcf]/55"
                >
                    {{ hero.secondary }}
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <Link
                        href="/about"
                        class="group inline-flex items-center gap-3 rounded-full bg-[#e4bc19] px-5 py-3 text-sm font-bold tracking-[0.12em] text-[#073b2a] uppercase"
                    >
                        Discover REL <ArrowUpRightIcon class="size-4" />
                    </Link>
                    <Link
                        href="/contact"
                        class="inline-flex items-center rounded-full border border-[#f7edcf]/25 px-5 py-3 text-sm tracking-[0.12em] text-[#f7edcf]/75 uppercase"
                    >
                        Work with us
                    </Link>
                </div>
                <div v-if="stats.length" class="mt-16 flex gap-10">
                    <StatCard
                        v-for="stat in stats"
                        :key="stat.id"
                        :value="stat.value"
                        :label="stat.label"
                    />
                </div>
            </div>
            <CampaignShowcase :campaigns="campaigns" />
        </div>
    </section>

    <section class="bg-[#f7edcf] px-6 py-16 sm:px-10 lg:px-14 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <div class="grid gap-10 lg:grid-cols-[.8fr_1.2fr] lg:items-end">
                <div>
                    <p
                        class="text-xs font-bold tracking-[0.2em] text-[#073b2a]/50 uppercase"
                    >
                        Entertainment. Technology. Opportunity.
                    </p>
                    <h2
                        class="mt-3 text-4xl leading-none font-black tracking-[-0.06em] uppercase sm:text-6xl"
                    >
                        Digital games, carried by media.
                    </h2>
                </div>
                <p class="max-w-xl text-base leading-7 text-[#073b2a]/65">
                    We combine digital technology with the reach and influence
                    of radio and television to create engaging gaming
                    experiences, new audience opportunities and potential
                    revenue streams for media partners.
                </p>
            </div>
            <div class="mt-12 grid gap-4 sm:grid-cols-3">
                <Link
                    href="/how-it-works"
                    class="group rounded-2xl bg-white/60 p-6"
                >
                    <span class="text-4xl font-black text-[#e4bc19]">01</span>
                    <h3 class="mt-10 text-xl font-black uppercase">
                        How it works
                    </h3>
                    <p class="mt-3 text-sm leading-6 text-[#073b2a]/60">
                        From a station shortcode to mobile-money payment, draw
                        and payout.
                    </p>
                    <ArrowUpRightIcon class="mt-6 size-5" />
                </Link>
                <Link
                    href="/raffles"
                    class="group rounded-2xl bg-[#e4bc19] p-6"
                >
                    <span class="text-4xl font-black">02</span>
                    <h3 class="mt-10 text-xl font-black uppercase">
                        Our raffles
                    </h3>
                    <p class="mt-3 text-sm leading-6 text-[#073b2a]/70">
                        Presenter, bonus and jackpot formats built for
                        programming.
                    </p>
                    <ArrowUpRightIcon class="mt-6 size-5" />
                </Link>
                <Link
                    href="/technology"
                    class="group rounded-2xl bg-[#073b2a] p-6 text-[#f7edcf]"
                >
                    <span class="text-4xl font-black text-[#e4bc19]">03</span>
                    <h3 class="mt-10 text-xl font-black uppercase">
                        Built for trust
                    </h3>
                    <p class="mt-3 text-sm leading-6 text-[#f7edcf]/60">
                        Dashboards, randomisation and reporting for accountable
                        operations.
                    </p>
                    <ArrowUpRightIcon class="mt-6 size-5 text-[#e4bc19]" />
                </Link>
            </div>
        </div>
    </section>
</template>
