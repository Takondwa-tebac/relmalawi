<script setup lang="ts">
import { Check } from '@lucide/vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import CtaPair from '@/components/guest/CtaPair.vue';
import DarkBand from '@/components/guest/DarkBand.vue';
import FeatureCard from '@/components/guest/FeatureCard.vue';
import FeatureRow from '@/components/guest/FeatureRow.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import SectionHeading from '@/components/guest/SectionHeading.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { FeaturesByGroup } from '@/types/features';

defineOptions({ layout: GuestLayout });

const props = defineProps<{ page: PageIntroData; features: FeaturesByGroup }>();

const commitments = computed(() => props.features['commitments'] ?? []);
const rows = computed(() => props.features['rows'] ?? []);
const promisesIntro = computed(() => props.features['promisesIntro']?.[0]);
const promises = computed(() => props.features['promises'] ?? []);
const standard = computed(() => props.features['standard']?.[0]);
const cta = computed(() => props.features['cta']?.[0]);
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
            <div v-if="commitments.length" class="mt-16 grid gap-5 sm:grid-cols-2">
                <FeatureCard
                    v-for="(commitment, i) in commitments"
                    :key="commitment.id"
                    :feature="commitment"
                    :index="i"
                    variant="commitment"
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
    <DarkBand v-if="standard" :block="standard" split />
    <section
        v-if="promises.length"
        class="bg-[#f7edcf] px-5 py-16 sm:px-10 lg:py-24"
    >
        <div class="mx-auto w-full max-w-7xl">
            <SectionHeading v-if="promisesIntro" :block="promisesIntro" />
            <ul class="mt-10 grid gap-3 md:grid-cols-2">
                <li
                    v-for="promise in promises"
                    :key="promise.id"
                    class="flex items-start gap-4 rounded-2xl bg-white/60 p-5"
                >
                    <span
                        class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-[#e4bc19]"
                    >
                        <Check class="size-3.5" />
                    </span>
                    <span class="text-sm font-bold leading-6 text-[#073b2a]">
                        {{ promise.title }}
                    </span>
                </li>
            </ul>
        </div>
    </section>
    <CtaPair v-if="cta" :block="cta" />
</template>
