<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { ArrowUpRight, Users } from '@lucide/vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import TeamCard from '@/components/guest/TeamCard.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData, SiteSettings } from '@/types';
import type { TeamMemberData } from '@/types/people';

defineOptions({ layout: GuestLayout });

defineProps<{ page: PageIntroData; team: TeamMemberData[] }>();

const site = usePage<{ site: SiteSettings }>().props.site;

// Generic "how we work" blocks; to move to the Feature model (Stream C).
const principles = [
    ['01', 'Listen first. Every partner and audience has context.'],
    ['02', 'Build together. We do not parachute in with a script.'],
    ['03', 'Own the outcome. Clear reporting is part of the work.'],
];
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
            <div class="mt-16 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <TeamCard
                    v-for="member in team"
                    :key="member.id"
                    :member="member"
                />
            </div>
        </div>
    </section>
    <section class="bg-[#073b2a] px-5 py-16 text-[#f7edcf] sm:px-10 lg:py-24">
        <div
            class="mx-auto grid w-full max-w-7xl gap-10 md:grid-cols-[.8fr_1.2fr] md:items-end"
        >
            <div>
                <p
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
                >
                    How we work
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    No spectators.<br />Only collaborators.
                </h2>
            </div>
            <div class="grid gap-5 sm:grid-cols-3">
                <div
                    v-for="[number, text] in principles"
                    :key="number"
                    class="border-t border-[#f7edcf]/20 pt-4"
                >
                    <strong class="text-3xl text-[#e4bc19]">{{ number }}</strong>
                    <p class="mt-3 text-sm leading-6 text-[#f7edcf]/65">
                        {{ text }}
                    </p>
                </div>
            </div>
        </div>
        <div
            class="mx-auto mt-14 flex w-full max-w-7xl flex-col gap-5 border-t border-[#f7edcf]/15 pt-8 sm:flex-row sm:items-center sm:justify-between"
        >
            <span class="flex items-center gap-3 text-lg font-bold">
                <Users class="text-[#e4bc19]" /> Want to join the network?
            </span>
            <a
                :href="`mailto:${site.contact_email}`"
                class="group inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-[#e4bc19]"
            >
                Introduce yourself
                <ArrowUpRight
                    class="size-4 transition-transform group-hover:-translate-y-1 group-hover:translate-x-1"
                />
            </a>
        </div>
    </section>
</template>
