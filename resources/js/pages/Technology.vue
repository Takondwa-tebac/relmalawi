<script setup lang="ts">
import { Check } from '@lucide/vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import CtaPair from '@/components/guest/CtaPair.vue';
import DarkBand from '@/components/guest/DarkBand.vue';
import FeatureCard from '@/components/guest/FeatureCard.vue';
import FeatureIcon from '@/components/guest/FeatureIcon.vue';
import FeatureRow from '@/components/guest/FeatureRow.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import SectionHeading from '@/components/guest/SectionHeading.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { splitBody } from '@/lib/featureBody';
import type { PageIntroData } from '@/types';
import type { FeaturesByGroup } from '@/types/features';

defineOptions({ layout: GuestLayout });

const props = defineProps<{ page: PageIntroData; features: FeaturesByGroup }>();

const capabilities = computed(() => props.features['capabilities'] ?? []);
const rows = computed(() => props.features['rows'] ?? []);
const dashboardsIntro = computed(() => props.features['dashboardsIntro']?.[0]);
const dashboards = computed(() => props.features['dashboards'] ?? []);
const reportsIntro = computed(() => props.features['reportsIntro']?.[0]);
const reports = computed(() => props.features['reports'] ?? []);
const ussd = computed(() => props.features['ussd']?.[0]);
const runsIntro = computed(() => props.features['runsIntro']?.[0]);
const runs = computed(() => props.features['runs'] ?? []);
const accountability = computed(() => props.features['accountability']?.[0]);
const cta = computed(() => props.features['cta']?.[0]);
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
            <div v-if="capabilities.length" class="mt-16 grid gap-4 sm:grid-cols-2">
                <FeatureCard
                    v-for="capability in capabilities"
                    :key="capability.id"
                    :feature="capability"
                    variant="capability"
                />
            </div>
        </div>
    </section>
    <section
        v-if="rows.length"
        class="bg-white/60 px-5 py-16 sm:px-10 lg:py-24"
    >
        <div class="mx-auto grid w-full max-w-7xl gap-20 lg:gap-28">
            <FeatureRow
                v-for="(row, i) in rows"
                :key="row.id"
                :feature="row"
                :reverse="i % 2 === 1"
            />
        </div>
    </section>
    <section
        v-if="dashboards.length"
        class="bg-[#073b2a] px-5 py-16 text-[#f7edcf] sm:px-10 lg:py-24"
    >
        <div class="mx-auto w-full max-w-7xl">
            <SectionHeading v-if="dashboardsIntro" :block="dashboardsIntro" dark />
            <div class="mt-10 grid gap-5 lg:grid-cols-3">
                <article
                    v-for="role in dashboards"
                    :key="role.id"
                    class="rounded-3xl border border-[#f7edcf]/15 p-7"
                >
                    <FeatureIcon :name="role.icon" class="size-8 text-[#e4bc19]" />
                    <h3 class="mt-8 text-2xl font-black uppercase tracking-[-0.04em]">
                        {{ role.title }}
                    </h3>
                    <p
                        v-for="(para, i) in splitBody(role.body).paragraphs"
                        :key="i"
                        class="mt-3 text-sm leading-7 text-[#f7edcf]/65"
                    >
                        {{ para }}
                    </p>
                    <ul class="mt-5 grid gap-2">
                        <li
                            v-for="item in splitBody(role.body).bullets"
                            :key="item"
                            class="flex items-start gap-2 text-sm font-bold"
                        >
                            <Check class="mt-0.5 size-4 shrink-0 text-[#e4bc19]" />
                            {{ item }}
                        </li>
                    </ul>
                </article>
            </div>
        </div>
    </section>
    <section
        v-if="reports.length"
        class="bg-[#f7edcf] px-5 py-16 sm:px-10 lg:py-24"
    >
        <div class="mx-auto w-full max-w-7xl">
            <SectionHeading v-if="reportsIntro" :block="reportsIntro" />
            <ul class="mt-10 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <li
                    v-for="report in reports"
                    :key="report.id"
                    class="flex items-center gap-3 rounded-2xl bg-white/60 p-5 text-sm font-black uppercase tracking-[-0.02em]"
                >
                    <span
                        class="flex size-6 shrink-0 items-center justify-center rounded-full bg-[#e4bc19]"
                    >
                        <Check class="size-3.5" />
                    </span>
                    {{ report.title }}
                </li>
            </ul>
        </div>
    </section>
    <section v-if="ussd" class="bg-white/60 px-5 py-16 sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <FeatureRow :feature="ussd" reverse />
        </div>
    </section>
    <section
        v-if="runs.length"
        class="bg-[#f7edcf] px-5 py-16 sm:px-10 lg:py-24"
    >
        <div class="mx-auto w-full max-w-7xl">
            <SectionHeading v-if="runsIntro" :block="runsIntro" />
            <ul class="mt-10 grid gap-4 sm:grid-cols-2">
                <li
                    v-for="item in runs"
                    :key="item.id"
                    class="flex items-center gap-4 rounded-3xl border border-[#073b2a]/10 bg-white/60 p-6"
                >
                    <FeatureIcon
                        :name="item.icon"
                        class="size-7 shrink-0 text-[#e4bc19]"
                    />
                    <span class="text-base font-black uppercase tracking-[-0.03em]">
                        {{ item.title }}
                    </span>
                </li>
            </ul>
        </div>
    </section>
    <DarkBand v-if="accountability" :block="accountability" />
    <CtaPair v-if="cta" :block="cta" />
</template>
