<script setup lang="ts">
import type { FeatureItem } from '@/types/features';

/** Gold call-to-action band driven by a feature row (eyebrow, title, meta.button_*). */
defineProps<{ block: FeatureItem }>();
</script>

<template>
    <section class="bg-[#e4bc19] px-5 py-14 sm:px-10 lg:py-20">
        <div
            class="mx-auto flex w-full max-w-7xl flex-col gap-5 md:flex-row md:items-center md:justify-between"
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
            <a
                v-if="block.meta.button_label && block.meta.button_url"
                :href="block.meta.button_url"
                class="inline-flex items-center gap-2 rounded-full bg-[#073b2a] px-5 py-3 text-xs font-bold uppercase tracking-[0.14em] text-[#f7edcf]"
            >
                {{ block.meta.button_label }}
            </a>
        </div>
    </section>
</template>
