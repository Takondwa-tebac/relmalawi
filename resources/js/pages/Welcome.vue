<script setup lang="ts">
import { ArrowUpRightIcon } from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import CampaignShowcase from '@/components/guest/CampaignShowCase.vue';
import StatCard from '@/components/guest/StatsCard.vue';
import { computed } from 'vue';
import CtaBand from '@/components/guest/CtaBand.vue';
import FaqAccordion from '@/components/guest/FaqAccordion.vue';
import FeatureIcon from '@/components/guest/FeatureIcon.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { FaqData } from '@/types/faq';
import type { FeaturesByGroup } from '@/types/features';
import type {
    CampaignData,
    HomeHero,
    HomePartnerData,
    StatData,
} from '@/types/home';

defineOptions({ layout: GuestLayout });

const props = defineProps<{
    page: PageIntroData;
    hero: HomeHero;
    campaigns: CampaignData[];
    stats: StatData[];
    features: FeaturesByGroup;
    mediaPartners: HomePartnerData[];
    paymentPartners: HomePartnerData[];
    faqs: FaqData[];
}>();

const intro = computed(() => props.features['intro']?.[0]);
const headlineStats = computed(() => props.features['stats'] ?? []);
const stepsHeading = computed(() => props.features['steps_heading']?.[0]);
const steps = computed(() => props.features['steps'] ?? []);
const audiences = computed(() => props.features['audiences'] ?? []);
const transparency = computed(() => props.features['transparency']?.[0]);
const transparencyItems = computed(
    () => props.features['transparency_items'] ?? [],
);
const payments = computed(() => props.features['payments']?.[0]);
const faqHeading = computed(() => props.features['faq']?.[0]);
const cta = computed(() => props.features['cta']?.[0]);

const audienceStyles = [
    { card: 'bg-white/60', eyebrow: 'text-[#073b2a]/50', body: 'text-[#073b2a]/65', icon: 'text-[#e4bc19]' },
    { card: 'bg-[#e4bc19]', eyebrow: 'text-[#073b2a]/60', body: 'text-[#073b2a]/70', icon: 'text-[#073b2a]' },
    { card: 'bg-[#073b2a] text-[#f7edcf]', eyebrow: 'text-[#e4bc19]', body: 'text-[#f7edcf]/60', icon: 'text-[#e4bc19]' },
];
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


    <!-- Trusted by -->
    <section
        v-if="mediaPartners.length"
        class="bg-[#f7edcf] px-6 pt-14 sm:px-10 lg:px-14"
    >
        <div class="mx-auto w-full max-w-7xl">
            <p
                class="text-center text-xs font-bold tracking-[0.2em] text-[#073b2a]/50 uppercase"
            >
                Trusted by leading Malawian media houses
            </p>
            <ul
                class="mt-8 flex flex-wrap items-center justify-center gap-x-10 gap-y-6"
            >
                <li v-for="partner in mediaPartners" :key="partner.id">
                    <img
                        :src="partner.logo_url ?? undefined"
                        :alt="partner.name"
                        loading="lazy"
                        class="h-12 w-auto max-w-32 object-contain"
                    />
                </li>
            </ul>
        </div>
    </section>

    <!-- Intro -->
    <section
        v-if="intro"
        class="bg-[#f7edcf] px-6 py-16 sm:px-10 lg:px-14 lg:py-24"
    >
        <div class="mx-auto w-full max-w-7xl">
            <div class="grid gap-10 lg:grid-cols-[.8fr_1.2fr] lg:items-end">
                <div>
                    <p
                        v-if="intro.eyebrow"
                        class="text-xs font-bold tracking-[0.2em] text-[#073b2a]/50 uppercase"
                    >
                        {{ intro.eyebrow }}
                    </p>
                    <h2
                        class="mt-3 text-4xl leading-none font-black tracking-[-0.06em] uppercase sm:text-6xl"
                    >
                        {{ intro.title }}
                    </h2>
                </div>
                <p
                    v-if="intro.body"
                    class="max-w-xl text-base leading-7 text-[#073b2a]/65"
                >
                    {{ intro.body }}
                </p>
            </div>
        </div>
    </section>

    <!-- Headline stats -->
    <section
        v-if="headlineStats.length"
        class="bg-[#e4bc19] px-6 py-14 sm:px-10 lg:px-14 lg:py-20"
    >
        <dl
            class="mx-auto grid w-full max-w-7xl gap-10 sm:grid-cols-2 lg:grid-cols-4"
        >
            <div
                v-for="stat in headlineStats"
                :key="stat.id"
                class="border-t-4 border-[#073b2a] pt-5"
            >
                <dt
                    class="text-6xl leading-none font-black tracking-[-0.06em] uppercase sm:text-7xl"
                >
                    {{ stat.title }}
                </dt>
                <dd class="mt-4 max-w-56 text-sm leading-6 text-[#073b2a]/75">
                    {{ stat.body }}
                </dd>
            </div>
        </dl>
    </section>

    <!-- How playing works -->
    <section
        v-if="steps.length"
        class="bg-[#f7edcf] px-6 py-16 sm:px-10 lg:px-14 lg:py-24"
    >
        <div class="mx-auto w-full max-w-7xl">
            <div
                v-if="stepsHeading"
                class="grid gap-8 lg:grid-cols-[1.2fr_.8fr] lg:items-end"
            >
                <div>
                    <p
                        v-if="stepsHeading.eyebrow"
                        class="text-xs font-bold tracking-[0.2em] text-[#073b2a]/50 uppercase"
                    >
                        {{ stepsHeading.eyebrow }}
                    </p>
                    <h2
                        class="mt-3 max-w-3xl text-4xl leading-none font-black tracking-[-0.06em] uppercase sm:text-6xl"
                    >
                        {{ stepsHeading.title }}
                    </h2>
                </div>
                <div>
                    <p
                        v-if="stepsHeading.body"
                        class="max-w-md text-base leading-7 text-[#073b2a]/65"
                    >
                        {{ stepsHeading.body }}
                    </p>
                    <Link
                        v-if="stepsHeading.meta.button_url"
                        :href="stepsHeading.meta.button_url"
                        class="mt-6 inline-flex items-center gap-2 rounded-full bg-[#073b2a] px-5 py-3 text-xs font-bold tracking-[0.14em] text-[#f7edcf] uppercase"
                    >
                        {{ stepsHeading.meta.button_label }}
                        <ArrowUpRightIcon class="size-4" />
                    </Link>
                </div>
            </div>
            <ol class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <li
                    v-for="(step, i) in steps"
                    :key="step.id"
                    class="rounded-3xl border border-[#073b2a]/10 bg-white/60 p-7"
                >
                    <div class="flex items-center justify-between">
                        <FeatureIcon
                            :name="step.icon"
                            class="size-8 text-[#e4bc19]"
                        />
                        <span class="text-sm font-black text-[#073b2a]/30">
                            0{{ i + 1 }}
                        </span>
                    </div>
                    <h3
                        class="mt-12 text-xl font-black tracking-[-0.03em] uppercase"
                    >
                        {{ step.title }}
                    </h3>
                    <p
                        v-if="step.body"
                        class="mt-3 text-sm leading-6 text-[#073b2a]/65"
                    >
                        {{ step.body }}
                    </p>
                </li>
            </ol>
        </div>
    </section>

    <!-- Audiences -->
    <section
        v-if="audiences.length"
        class="bg-[#f7edcf] px-6 pb-16 sm:px-10 lg:px-14 lg:pb-24"
    >
        <div class="mx-auto grid w-full max-w-7xl gap-4 lg:grid-cols-3">
            <Link
                v-for="(item, i) in audiences"
                :key="item.id"
                :href="item.meta.button_url ?? '#'"
                :class="[
                    'group flex flex-col rounded-3xl p-7 sm:p-9',
                    audienceStyles[i % audienceStyles.length].card,
                ]"
            >
                <FeatureIcon
                    :name="item.icon"
                    :class="['size-9', audienceStyles[i % audienceStyles.length].icon]"
                />
                <p
                    :class="[
                        'mt-12 text-xs font-bold tracking-[0.2em] uppercase',
                        audienceStyles[i % audienceStyles.length].eyebrow,
                    ]"
                >
                    {{ item.eyebrow }}
                </p>
                <h3
                    class="mt-3 text-2xl leading-tight font-black tracking-[-0.03em] uppercase"
                >
                    {{ item.title }}
                </h3>
                <p
                    v-if="item.body"
                    :class="[
                        'mt-4 text-sm leading-7',
                        audienceStyles[i % audienceStyles.length].body,
                    ]"
                >
                    {{ item.body }}
                </p>
                <span
                    class="mt-8 inline-flex items-center gap-2 text-xs font-bold tracking-[0.14em] uppercase"
                >
                    {{ item.meta.button_label }}
                    <ArrowUpRightIcon class="size-4" />
                </span>
            </Link>
        </div>
    </section>

    <!-- Transparency -->
    <section
        v-if="transparency"
        class="bg-[#073b2a] px-6 py-16 text-[#f7edcf] sm:px-10 lg:px-14 lg:py-24"
    >
        <div
            class="mx-auto grid w-full max-w-7xl gap-12 lg:grid-cols-2 lg:items-center"
        >
            <div>
                <p
                    v-if="transparency.eyebrow"
                    class="text-xs font-bold tracking-[0.2em] text-[#e4bc19] uppercase"
                >
                    {{ transparency.eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl leading-none font-black tracking-[-0.06em] uppercase sm:text-6xl"
                >
                    {{ transparency.title }}
                </h2>
                <p
                    v-if="transparency.body"
                    class="mt-7 max-w-xl text-base leading-8 text-[#f7edcf]/65"
                >
                    {{ transparency.body }}
                </p>
                <Link
                    v-if="transparency.meta.button_url"
                    :href="transparency.meta.button_url"
                    class="mt-8 inline-flex items-center gap-2 rounded-full bg-[#e4bc19] px-5 py-3 text-xs font-bold tracking-[0.14em] text-[#073b2a] uppercase"
                >
                    {{ transparency.meta.button_label }}
                    <ArrowUpRightIcon class="size-4" />
                </Link>
            </div>
            <ul
                v-if="transparencyItems.length"
                class="divide-y divide-[#f7edcf]/10 rounded-3xl border border-[#f7edcf]/10 bg-[#f7edcf]/5 px-6 sm:px-8"
            >
                <li
                    v-for="item in transparencyItems"
                    :key="item.id"
                    class="flex items-center gap-4 py-5"
                >
                    <FeatureIcon
                        :name="item.icon ?? 'check-circle'"
                        class="size-6 shrink-0 text-[#e4bc19]"
                    />
                    <span class="text-base font-bold">{{ item.title }}</span>
                </li>
            </ul>
        </div>
    </section>

    <!-- Payments -->
    <section
        v-if="payments && paymentPartners.length"
        class="bg-[#f7edcf] px-6 py-16 sm:px-10 lg:px-14 lg:py-24"
    >
        <div
            class="mx-auto grid w-full max-w-7xl gap-10 lg:grid-cols-2 lg:items-center"
        >
            <div>
                <p
                    v-if="payments.eyebrow"
                    class="text-xs font-bold tracking-[0.2em] text-[#073b2a]/50 uppercase"
                >
                    {{ payments.eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl leading-none font-black tracking-[-0.06em] uppercase sm:text-5xl"
                >
                    {{ payments.title }}
                </h2>
                <p
                    v-if="payments.body"
                    class="mt-6 max-w-xl text-base leading-7 text-[#073b2a]/65"
                >
                    {{ payments.body }}
                </p>
            </div>
            <ul class="grid gap-4 sm:grid-cols-2">
                <li
                    v-for="partner in paymentPartners"
                    :key="partner.id"
                    class="flex flex-col items-center justify-center gap-4 rounded-3xl bg-white/60 p-8"
                >
                    <img
                        :src="partner.logo_url ?? undefined"
                        :alt="partner.name"
                        loading="lazy"
                        class="h-16 w-auto max-w-full object-contain"
                    />
                    <span
                        class="text-xs font-bold tracking-[0.16em] text-[#073b2a]/55 uppercase"
                    >
                        {{ partner.name }}
                    </span>
                </li>
            </ul>
        </div>
    </section>

    <!-- FAQ teaser -->
    <section
        v-if="faqs.length"
        class="bg-white/60 px-6 py-16 sm:px-10 lg:px-14 lg:py-24"
    >
        <div class="mx-auto grid w-full max-w-7xl gap-12 lg:grid-cols-[.7fr_1.3fr]">
            <div v-if="faqHeading">
                <p
                    v-if="faqHeading.eyebrow"
                    class="text-xs font-bold tracking-[0.2em] text-[#073b2a]/50 uppercase"
                >
                    {{ faqHeading.eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl leading-none font-black tracking-[-0.06em] uppercase sm:text-6xl"
                >
                    {{ faqHeading.title }}
                </h2>
                <Link
                    v-if="faqHeading.meta.button_url"
                    :href="faqHeading.meta.button_url"
                    class="mt-8 inline-flex items-center gap-2 rounded-full bg-[#073b2a] px-5 py-3 text-xs font-bold tracking-[0.14em] text-[#f7edcf] uppercase"
                >
                    {{ faqHeading.meta.button_label }}
                    <ArrowUpRightIcon class="size-4" />
                </Link>
            </div>
            <FaqAccordion :faqs="faqs" />
        </div>
    </section>

    <CtaBand v-if="cta" :block="cta" />
</template>
