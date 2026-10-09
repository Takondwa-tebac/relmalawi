<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Check } from '@lucide/vue';
import { computed } from 'vue';
import CtaBand from '@/components/guest/CtaBand.vue';
import FeatureIcon from '@/components/guest/FeatureIcon.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { CampaignData } from '@/types/home';
import type { FeatureItem, FeaturesByGroup } from '@/types/features';

defineOptions({ layout: GuestLayout });

const props = defineProps<{
    page: PageIntroData;
    features: FeaturesByGroup;
    campaigns: CampaignData[];
}>();

const formats = computed(() => props.features['formats'] ?? []);
const campaign = computed(() => props.features['campaign']?.[0]);
const channelsIntro = computed(() => props.features['channelsIntro']?.[0]);
const channels = computed(() => props.features['channels'] ?? []);
const band = computed(() => props.features['band']?.[0]);
const cta = computed(() => props.features['cta']?.[0]);

const lines = (feature: FeatureItem | undefined): string[] =>
    String(feature?.meta?.bullets ?? '')
        .split('\n')
        .map((line) => line.trim())
        .filter(Boolean);

const num = (i: number) => String(i + 1).padStart(2, '0');
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
            <div v-if="formats.length" class="mt-16 grid gap-5 lg:grid-cols-3">
                <article
                    v-for="(format, i) in formats"
                    :key="format.id"
                    class="flex flex-col rounded-3xl bg-[#073b2a] p-8 text-[#f7edcf] sm:p-10"
                >
                    <div class="flex items-center justify-between">
                        <FeatureIcon
                            :name="format.icon"
                            class="size-9 text-[#e4bc19]"
                        />
                        <span class="text-sm font-black text-[#f7edcf]/35">{{
                            num(i)
                        }}</span>
                    </div>
                    <p
                        v-if="format.eyebrow"
                        class="mt-12 text-xs font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
                    >
                        {{ format.eyebrow }}
                    </p>
                    <h2
                        class="mt-3 text-3xl font-black uppercase leading-none tracking-[-0.04em]"
                    >
                        {{ format.title }}
                    </h2>
                    <p
                        v-if="format.body"
                        class="mt-4 text-sm leading-7 text-[#f7edcf]/65"
                    >
                        {{ format.body }}
                    </p>
                    <p
                        v-if="format.meta.best_for"
                        class="mt-5 rounded-xl bg-[#f7edcf]/10 px-4 py-3 text-sm font-bold"
                    >
                        {{ format.meta.best_for }}
                    </p>
                    <ul class="mt-6 space-y-3 text-sm text-[#f7edcf]/80">
                        <li
                            v-for="line in lines(format)"
                            :key="line"
                            class="flex gap-3"
                        >
                            <Check class="mt-0.5 size-4 shrink-0 text-[#e4bc19]" />
                            {{ line }}
                        </li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section v-if="campaign" class="bg-white/60 px-5 py-16 sm:px-10 lg:px-14 lg:py-24">
        <div class="mx-auto max-w-7xl">
            <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
                <div>
                    <p
                        v-if="campaign.eyebrow"
                        class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/55"
                    >
                        {{ campaign.eyebrow }}
                    </p>
                    <h2
                        class="mt-4 text-4xl font-black uppercase leading-none tracking-[-0.06em] text-[#073b2a] sm:text-5xl"
                    >
                        {{ campaign.title }}
                    </h2>
                    <p
                        v-if="campaign.body"
                        class="mt-6 max-w-xl text-base leading-8 text-[#073b2a]/65"
                    >
                        {{ campaign.body }}
                    </p>
                    <ul class="mt-6 space-y-3 text-[#073b2a]">
                        <li
                            v-for="line in lines(campaign)"
                            :key="line"
                            class="flex gap-3 text-sm font-semibold"
                        >
                            <Check class="mt-0.5 size-4 shrink-0 text-[#073b2a]" />
                            {{ line }}
                        </li>
                    </ul>
                </div>
                <div class="rounded-3xl bg-[#e4bc19] p-8 sm:p-12">
                    <FeatureIcon
                        :name="campaign.icon"
                        class="size-12 text-[#073b2a]"
                    />
                    <p
                        class="mt-16 text-3xl font-black uppercase leading-none tracking-[-0.05em] text-[#073b2a]"
                    >
                        Campaign name.<br />Teasers.<br />Adverts.
                    </p>
                </div>
            </div>
            <div
                v-if="campaigns.length"
                class="mt-12 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4"
            >
                <figure
                    v-for="item in campaigns"
                    :key="item.id"
                    class="overflow-hidden rounded-2xl bg-[#073b2a]/10"
                >
                    <img
                        v-if="item.thumb"
                        :src="item.thumb"
                        :alt="item.alt"
                        loading="lazy"
                        class="aspect-[4/3] w-full object-cover"
                    />
                </figure>
            </div>
        </div>
    </section>

    <section
        v-if="channels.length"
        class="bg-[#f7edcf] px-5 py-16 sm:px-10 lg:px-14 lg:py-24"
    >
        <div class="mx-auto max-w-7xl">
            <div v-if="channelsIntro" class="max-w-3xl">
                <p
                    v-if="channelsIntro.eyebrow"
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/55"
                >
                    {{ channelsIntro.eyebrow }}
                </p>
                <h2
                    class="mt-4 text-4xl font-black uppercase leading-none tracking-[-0.06em] text-[#073b2a] sm:text-6xl"
                >
                    {{ channelsIntro.title }}
                </h2>
                <p
                    v-if="channelsIntro.body"
                    class="mt-6 max-w-2xl text-base leading-8 text-[#073b2a]/65"
                >
                    {{ channelsIntro.body }}
                </p>
            </div>
            <ol class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <li
                    v-for="(channel, i) in channels"
                    :key="channel.id"
                    class="rounded-3xl bg-white/60 p-7 sm:p-9"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-5xl font-black text-[#e4bc19]">{{
                            num(i)
                        }}</span>
                        <FeatureIcon
                            :name="channel.icon"
                            class="size-8 text-[#073b2a]"
                        />
                    </div>
                    <h3 class="mt-10 text-2xl font-black uppercase">
                        {{ channel.title }}
                    </h3>
                    <p
                        v-if="channel.body"
                        class="mt-3 text-sm leading-7 text-[#073b2a]/60"
                    >
                        {{ channel.body }}
                    </p>
                </li>
            </ol>
        </div>
    </section>

    <section
        v-if="band"
        class="bg-[#073b2a] px-5 py-16 text-[#f7edcf] sm:px-10 lg:px-14 lg:py-24"
    >
        <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-2 lg:gap-16">
            <div>
                <p
                    v-if="band.eyebrow"
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
                >
                    {{ band.eyebrow }}
                </p>
                <h2
                    class="mt-4 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ band.title }}
                </h2>
                <p
                    v-if="band.body"
                    class="mt-6 max-w-xl text-base leading-8 text-[#f7edcf]/65"
                >
                    {{ band.body }}
                </p>
            </div>
            <ul class="grid gap-3 sm:grid-cols-2">
                <li
                    v-for="(line, i) in lines(band)"
                    :key="line"
                    class="rounded-2xl border border-[#f7edcf]/15 p-6"
                >
                    <span class="text-sm font-black text-[#e4bc19]">{{
                        num(i)
                    }}</span>
                    <p class="mt-8 text-xl font-black uppercase">{{ line }}</p>
                </li>
            </ul>
        </div>
    </section>

    <CtaBand v-if="cta" :block="cta" />
</template>
