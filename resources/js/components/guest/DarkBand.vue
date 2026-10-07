<script setup lang="ts">
import type { FeatureItem } from '@/types/features';

/** Full-width dark green band: eyebrow, big title and body paragraphs. */
defineProps<{ block: FeatureItem; split?: boolean }>();
</script>

<template>
    <section
        class="bg-[#073b2a] px-5 py-16 text-[#f7edcf] sm:px-10 lg:py-24"
        :class="split ? '' : 'lg:px-14'"
    >
        <div
            v-if="split"
            class="mx-auto grid w-full max-w-7xl gap-10 md:grid-cols-2"
        >
            <div>
                <p
                    v-if="block.eyebrow"
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
                >
                    {{ block.eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ block.title }}
                </h2>
            </div>
            <div class="space-y-6 text-base leading-8 text-[#f7edcf]/65">
                <p v-for="(para, i) in (block.body ?? '').split(/\n{2,}/)" :key="i">
                    {{ para }}
                </p>
            </div>
        </div>
        <div v-else class="mx-auto max-w-7xl">
            <p
                v-if="block.eyebrow"
                class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
            >
                {{ block.eyebrow }}
            </p>
            <h2
                class="mt-4 max-w-3xl text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
            >
                {{ block.title }}
            </h2>
            <p
                v-if="block.body"
                class="mt-8 max-w-2xl text-base leading-7 text-[#f7edcf]/65"
            >
                {{ block.body }}
            </p>
        </div>
    </section>
</template>
