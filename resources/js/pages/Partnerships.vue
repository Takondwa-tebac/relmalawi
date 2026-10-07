<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { ArrowUpRight, RadioTower, Sparkles, TrendingUp } from '@lucide/vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import PartnerTile from '@/components/guest/PartnerTile.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData, SiteSettings } from '@/types';
import type { PartnerData } from '@/types/people';

defineOptions({ layout: GuestLayout });

defineProps<{ page: PageIntroData; partners: PartnerData[] }>();

const site = usePage<{ site: SiteSettings }>().props.site;

// Generic service cards; to move to the Feature model (Stream C).
const offers = [
    {
        title: 'Raffle draw management',
        text: 'From draw mechanics and entries to audit-ready reporting, we manage the moving parts that make promotions trustworthy.',
        icon: RadioTower,
    },
    {
        title: 'Media games',
        text: 'We design audience-first games that fit naturally into radio programming and give listeners a reason to participate.',
        icon: Sparkles,
    },
    {
        title: 'Umoja Promo Raffle & Pompo Draws',
        text: 'Our core formats connect partners with simple, memorable participation mechanics built for real reach across Malawi.',
        icon: TrendingUp,
    },
];
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
            <div class="mt-16 grid gap-5 lg:grid-cols-3">
                <article
                    v-for="(offer, index) in offers"
                    :key="offer.title"
                    class="group rounded-3xl bg-[#073b2a] p-7 text-[#f7edcf] transition-transform hover:-translate-y-1 sm:p-9"
                >
                    <div class="flex items-center justify-between">
                        <component
                            :is="offer.icon"
                            class="size-9 text-[#e4bc19]"
                        />
                        <span class="text-sm font-black text-[#f7edcf]/35"
                            >0{{ index + 1 }}</span
                        >
                    </div>
                    <h2
                        class="mt-16 text-2xl font-black uppercase leading-tight tracking-[-0.04em]"
                    >
                        {{ offer.title }}
                    </h2>
                    <p class="mt-4 text-sm leading-7 text-[#f7edcf]/60">
                        {{ offer.text }}
                    </p>
                    <div
                        class="mt-9 flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.16em] text-[#e4bc19]"
                    >
                        Built with media partners
                        <ArrowUpRight class="size-3" />
                    </div>
                </article>
            </div>
        </div>
    </section>
    <section class="bg-white/60 px-5 py-16 sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <div class="max-w-2xl">
                <p
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/50"
                >
                    Radio &amp; television partners
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    The platforms that carry the conversation.
                </h2>
                <p class="mt-6 text-base leading-7 text-[#073b2a]/65">
                    Our partnerships are built around reach, trust and local
                    relevance. These are the stations and media platforms that
                    help bring our games to audiences across Malawi.
                </p>
            </div>
            <div
                class="mt-12 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4"
            >
                <PartnerTile
                    v-for="partner in partners"
                    :key="partner.id"
                    :partner="partner"
                />
            </div>
        </div>
    </section>
    <section class="bg-[#e4bc19] px-5 py-14 text-[#073b2a] sm:px-10 lg:py-20">
        <div
            class="mx-auto flex w-full max-w-7xl flex-col gap-6 md:flex-row md:items-end md:justify-between"
        >
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em]">
                    Build with REL
                </p>
                <h2
                    class="mt-3 max-w-2xl text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    Bring us your audience, your brief or your next big idea.
                </h2>
            </div>
            <a
                :href="`mailto:${site.contact_email}`"
                class="inline-flex shrink-0 items-center gap-2 rounded-full bg-[#073b2a] px-5 py-3 text-xs font-bold uppercase tracking-[0.14em] text-[#f7edcf]"
            >
                Start a conversation <ArrowUpRight class="size-4" />
            </a>
        </div>
    </section>
</template>
