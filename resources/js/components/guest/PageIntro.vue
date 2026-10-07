<script setup lang="ts">
import type { PageIntroData } from '@/types';

defineProps<{ page: PageIntroData }>();
</script>

<template>
    <div class="max-w-3xl">
        <div
            v-if="page.eyebrow"
            class="mb-6 flex items-center gap-3 text-xs font-bold uppercase tracking-[0.25em] text-[#073b2a]/55"
        >
            <span class="h-1 w-10 bg-[#e4bc19]" />
            {{ page.eyebrow }}
        </div>
        <h1
            class="text-[clamp(3.5rem,9vw,7.5rem)] font-black uppercase leading-[0.82] tracking-[-0.08em] text-[#073b2a]"
        >
            {{ page.title }}
            <template v-if="page.title_accent">
                <br />
                <span class="text-[#e4bc19]">{{ page.title_accent }}</span>
            </template>
        </h1>
        <p
            v-if="page.description"
            class="mt-8 max-w-2xl text-lg leading-8 text-[#073b2a]/65"
        >
            {{ page.description }}
        </p>
    </div>
</template>
