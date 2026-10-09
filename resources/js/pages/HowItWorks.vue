<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {
    Banknote,
    Check,
    CheckCircle2,
    MessageSquare,
    Radio,
    Shuffle,
    Smartphone,
    Trophy,
    Wallet,
} from '@lucide/vue';
import type { Component } from 'vue';
import { computed } from 'vue';
import CtaBand from '@/components/guest/CtaBand.vue';
import FaqAccordion from '@/components/guest/FaqAccordion.vue';
import FeatureIcon from '@/components/guest/FeatureIcon.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import UssdPhone from '@/components/guest/UssdPhone.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { HowItWorksStepData } from '@/types/contact';
import type { FaqData } from '@/types/faq';
import type { FeatureItem, FeaturesByGroup } from '@/types/features';

defineOptions({ layout: GuestLayout });

const props = defineProps<{
    page: PageIntroData;
    steps: HowItWorksStepData[];
    features: FeaturesByGroup;
    faqs: FaqData[];
}>();

const icons: Record<string, Component> = {
    smartphone: Smartphone,
    wallet: Wallet,
    'message-square': MessageSquare,
    shuffle: Shuffle,
    trophy: Trophy,
    banknote: Banknote,
    radio: Radio,
    'check-circle': CheckCircle2,
};

const hero = computed(() => props.features['hero']?.[0]);
const ussdIntro = computed(() => props.features['ussdIntro']?.[0]);
const ussd = computed(() => props.features['ussd'] ?? []);
const behind = computed(() => props.features['behind'] ?? []);
const rolesIntro = computed(() => props.features['rolesIntro']?.[0]);
const roles = computed(() => props.features['roles'] ?? []);
const faqIntro = computed(() => props.features['faqIntro']?.[0]);
const cta = computed(() => props.features['cta']?.[0]);

const lines = (feature: FeatureItem | undefined): string[] =>
    String(feature?.meta?.bullets ?? '')
        .split('\n')
        .map((line) => line.trim())
        .filter(Boolean);
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
            <div v-if="hero" class="mt-8 flex flex-wrap gap-3">
                <a
                    v-if="hero.meta.button_label && hero.meta.button_url"
                    :href="hero.meta.button_url"
                    class="rounded-full bg-[#073b2a] px-6 py-3 text-xs font-bold uppercase tracking-[0.14em] text-[#f7edcf]"
                >
                    {{ hero.meta.button_label }}
                </a>
                <a
                    v-if="hero.meta.secondary_label && hero.meta.secondary_url"
                    :href="String(hero.meta.secondary_url)"
                    class="rounded-full border border-[#073b2a] px-6 py-3 text-xs font-bold uppercase tracking-[0.14em] text-[#073b2a]"
                >
                    {{ hero.meta.secondary_label }}
                </a>
            </div>
            <ol class="mt-16 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <li
                    v-for="step in steps"
                    :key="step.id"
                    class="rounded-3xl bg-white/60 p-7"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-5xl font-black text-[#e4bc19]">{{
                            step.number
                        }}</span>
                        <component
                            :is="icons[step.icon ?? '']"
                            v-if="step.icon && icons[step.icon]"
                            class="size-8 text-[#073b2a]"
                        />
                    </div>
                    <h2 class="mt-10 text-xl font-black uppercase leading-tight">
                        {{ step.title }}
                    </h2>
                    <p
                        v-if="step.body"
                        class="mt-3 text-sm leading-7 text-[#073b2a]/65"
                    >
                        {{ step.body }}
                    </p>
                    <p
                        v-if="step.detail"
                        class="mt-3 text-sm font-semibold leading-6 text-[#073b2a]"
                    >
                        {{ step.detail }}
                    </p>
                </li>
            </ol>
        </div>
    </section>

    <section
        v-if="ussd.length"
        class="bg-[#073b2a] px-5 py-16 text-[#f7edcf] sm:px-10 lg:px-14 lg:py-24"
    >
        <div
            class="mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-2 lg:gap-16"
        >
            <div v-if="ussdIntro">
                <p
                    v-if="ussdIntro.eyebrow"
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
                >
                    {{ ussdIntro.eyebrow }}
                </p>
                <h2
                    class="mt-4 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ ussdIntro.title }}
                </h2>
                <p
                    v-if="ussdIntro.body"
                    class="mt-6 max-w-xl text-base leading-8 text-[#f7edcf]/65"
                >
                    {{ ussdIntro.body }}
                </p>
                <ul class="mt-6 space-y-3 text-sm">
                    <li
                        v-for="line in lines(ussdIntro)"
                        :key="line"
                        class="flex gap-3"
                    >
                        <Check class="mt-0.5 size-4 shrink-0 text-[#e4bc19]" />
                        {{ line }}
                    </li>
                </ul>
            </div>
            <UssdPhone :screens="ussd" />
        </div>
    </section>

    <section
        v-if="behind.length"
        class="bg-[#f7edcf] px-5 py-16 sm:px-10 lg:px-14 lg:py-24"
    >
        <div class="mx-auto max-w-7xl space-y-16 lg:space-y-24">
            <div
                v-for="(row, i) in behind"
                :key="row.id"
                class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16"
            >
                <div :class="i % 2 === 1 ? 'lg:order-2' : ''">
                    <p
                        v-if="row.eyebrow"
                        class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/55"
                    >
                        {{ row.eyebrow }}
                    </p>
                    <h2
                        class="mt-4 text-4xl font-black uppercase leading-none tracking-[-0.06em] text-[#073b2a] sm:text-5xl"
                    >
                        {{ row.title }}
                    </h2>
                    <p
                        v-if="row.body"
                        class="mt-6 max-w-xl text-base leading-8 text-[#073b2a]/65"
                    >
                        {{ row.body }}
                    </p>
                    <ul class="mt-6 space-y-3 text-[#073b2a]">
                        <li
                            v-for="line in lines(row)"
                            :key="line"
                            class="flex gap-3 text-sm font-semibold"
                        >
                            <Check class="mt-0.5 size-4 shrink-0" />
                            {{ line }}
                        </li>
                    </ul>
                </div>
                <div
                    class="rounded-3xl p-8 sm:p-12"
                    :class="
                        i % 2 === 1
                            ? 'bg-[#e4bc19] text-[#073b2a]'
                            : 'bg-[#073b2a] text-[#f7edcf]'
                    "
                >
                    <FeatureIcon
                        :name="row.icon"
                        class="size-12"
                        :class="i % 2 === 1 ? '' : 'text-[#e4bc19]'"
                    />
                    <p
                        v-if="row.meta.caption"
                        class="mt-16 text-3xl font-black uppercase leading-none tracking-[-0.05em]"
                    >
                        {{ row.meta.caption }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section
        v-if="roles.length"
        class="bg-[#e4bc19] px-5 py-16 sm:px-10 lg:px-14 lg:py-24"
    >
        <div class="mx-auto max-w-7xl">
            <div v-if="rolesIntro" class="max-w-3xl">
                <p
                    v-if="rolesIntro.eyebrow"
                    class="text-xs font-bold uppercase tracking-[0.2em]"
                >
                    {{ rolesIntro.eyebrow }}
                </p>
                <h2
                    class="mt-4 text-4xl font-black uppercase leading-none tracking-[-0.06em] text-[#073b2a] sm:text-6xl"
                >
                    {{ rolesIntro.title }}
                </h2>
                <p
                    v-if="rolesIntro.body"
                    class="mt-6 max-w-2xl text-base leading-8 text-[#073b2a]/75"
                >
                    {{ rolesIntro.body }}
                </p>
            </div>
            <div class="mt-12 grid gap-4 md:grid-cols-3">
                <article
                    v-for="role in roles"
                    :key="role.id"
                    class="rounded-3xl bg-white/45 p-7 sm:p-8"
                >
                    <FeatureIcon
                        :name="role.icon"
                        class="size-8 text-[#073b2a]"
                    />
                    <h3 class="mt-10 text-2xl font-black uppercase">
                        {{ role.title }}
                    </h3>
                    <p
                        v-if="role.body"
                        class="mt-3 text-sm leading-7 text-[#073b2a]/70"
                    >
                        {{ role.body }}
                    </p>
                    <ul class="mt-5 space-y-2 text-sm font-semibold text-[#073b2a]">
                        <li
                            v-for="line in lines(role)"
                            :key="line"
                            class="flex gap-3"
                        >
                            <Check class="mt-0.5 size-4 shrink-0" />
                            {{ line }}
                        </li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section
        v-if="faqs.length"
        class="bg-[#f7edcf] px-5 py-16 sm:px-10 lg:px-14 lg:py-24"
    >
        <div class="mx-auto max-w-4xl">
            <div v-if="faqIntro" class="mb-10">
                <p
                    v-if="faqIntro.eyebrow"
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/55"
                >
                    {{ faqIntro.eyebrow }}
                </p>
                <h2
                    class="mt-4 text-4xl font-black uppercase leading-none tracking-[-0.06em] text-[#073b2a] sm:text-5xl"
                >
                    {{ faqIntro.title }}
                </h2>
            </div>
            <FaqAccordion :faqs="faqs" />
        </div>
    </section>

    <CtaBand v-if="cta" :block="cta" />
</template>
