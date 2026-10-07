<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import DarkBand from '@/components/guest/DarkBand.vue';
import FeatureCard from '@/components/guest/FeatureCard.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { FeaturesByGroup } from '@/types/features';

defineOptions({ layout: GuestLayout });

const props = defineProps<{ page: PageIntroData; features: FeaturesByGroup }>();

const commitments = computed(
    () => props.features['commitments'] ?? [],
);
const standard = computed(() => props.features['standard']?.[0]);
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
    <DarkBand v-if="standard" :block="standard" split />
</template>
