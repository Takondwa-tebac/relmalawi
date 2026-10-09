<script setup lang="ts">
import type { FeatureItem } from '@/types/features';

/** Gold CTA band with a primary and an optional secondary button (meta.button_*, meta.secondary_*). */
defineProps<{ block: FeatureItem }>();
</script>

<template>
    <section class="bg-[#e4bc19] px-5 py-14 sm:px-10 lg:py-20">
        <div
            class="mx-auto flex w-full max-w-7xl flex-col gap-6 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <p
                    v-if="block.eyebrow"
                    class="text-xs font-bold uppercase tracking-[0.2em]"
                >
                    {{ block.eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ block.title }}
                </h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a
                    v-if="block.meta.button_label && block.meta.button_url"
                    :href="String(block.meta.button_url)"
                    class="inline-flex items-center gap-2 rounded-full bg-[#073b2a] px-5 py-3 text-xs font-bold uppercase tracking-[0.14em] text-[#f7edcf]"
                >
                    {{ block.meta.button_label }}
                </a>
                <a
                    v-if="block.meta.secondary_label && block.meta.secondary_url"
                    :href="String(block.meta.secondary_url)"
                    class="inline-flex items-center gap-2 rounded-full border border-[#073b2a] px-5 py-3 text-xs font-bold uppercase tracking-[0.14em] text-[#073b2a]"
                >
                    {{ block.meta.secondary_label }}
                </a>
            </div>
        </div>
    </section>
</template>
