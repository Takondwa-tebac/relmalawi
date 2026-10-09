<script setup lang="ts">
import type { FeatureItem } from '@/types/features';

/** Section heading driven by a feature row (eyebrow, title, body). */
defineProps<{ block: FeatureItem; dark?: boolean }>();
</script>

<template>
    <div class="max-w-3xl">
        <p
            v-if="block.eyebrow"
            class="text-xs font-bold uppercase tracking-[0.2em]"
            :class="dark ? 'text-[#e4bc19]' : 'text-[#073b2a]/55'"
        >
            {{ block.eyebrow }}
        </p>
        <h2
            class="mt-4 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-5xl"
            :class="dark ? 'text-[#f7edcf]' : 'text-[#073b2a]'"
        >
            {{ block.title }}
        </h2>
        <p
            v-if="block.body"
            class="mt-5 text-base leading-7"
            :class="dark ? 'text-[#f7edcf]/65' : 'text-[#073b2a]/65'"
        >
            {{ block.body }}
        </p>
    </div>
</template>
