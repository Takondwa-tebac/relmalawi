<script setup lang="ts">
import { computed } from 'vue';
import FeatureIcon from '@/components/guest/FeatureIcon.vue';
import type { FeatureItem } from '@/types/features';

/**
 * pillar: outlined card (About)   format: dark numbered card (Raffles)
 * capability: glass card (Technology)   commitment: soft card, icon + number row (Regulation)
 */
const props = defineProps<{
    feature: FeatureItem;
    variant: 'pillar' | 'format' | 'capability' | 'commitment';
    index?: number;
}>();

const number = computed(() => `0${(props.index ?? 0) + 1}`);

const styles = {
    pillar: {
        root: 'rounded-3xl border border-[#073b2a]/10 p-7',
        icon: 'size-8 text-[#e4bc19]',
        title: 'mt-12 text-2xl font-black uppercase tracking-[-0.04em]',
        body: 'mt-4 text-sm leading-7 text-[#073b2a]/65',
    },
    format: {
        root: 'rounded-3xl bg-[#073b2a] p-8 text-[#f7edcf] sm:p-10',
        icon: 'size-9 text-[#e4bc19]',
        title: 'mt-3 text-2xl font-black uppercase leading-tight',
        body: 'mt-4 text-sm leading-7 text-[#f7edcf]/60',
    },
    capability: {
        root: 'rounded-3xl border border-[#073b2a]/10 bg-white/60 p-7 sm:p-9',
        icon: 'size-8 text-[#e4bc19]',
        title: 'mt-12 text-2xl font-black uppercase',
        body: 'mt-4 text-sm leading-7 text-[#073b2a]/60',
    },
    commitment: {
        root: 'rounded-3xl bg-white/60 p-7 sm:p-9',
        icon: 'size-8 text-[#e4bc19]',
        title: 'mt-14 text-2xl font-black uppercase tracking-[-0.04em]',
        body: 'mt-4 max-w-lg text-sm leading-7 text-[#073b2a]/65',
    },
} as const;

const s = computed(() => styles[props.variant]);
</script>

<template>
    <article :class="s.root">
        <div v-if="variant === 'commitment'" class="flex items-center justify-between">
            <FeatureIcon :name="feature.icon" :class="s.icon" />
            <span class="text-sm font-black text-[#073b2a]/25">{{ number }}</span>
        </div>
        <FeatureIcon v-else :name="feature.icon" :class="s.icon" />
        <span
            v-if="variant === 'format'"
            class="mt-16 block text-sm font-black text-[#f7edcf]/35"
            >{{ number }}</span
        >
        <h2 :class="s.title">{{ feature.title }}</h2>
        <p v-if="feature.body" :class="s.body">{{ feature.body }}</p>
    </article>
</template>
