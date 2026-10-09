<script setup lang="ts">
import { Check } from '@lucide/vue';
import { computed } from 'vue';
import FeatureIcon from '@/components/guest/FeatureIcon.vue';
import { splitBody } from '@/lib/featureBody';
import type { FeatureItem } from '@/types/features';

/** Alternating feature row: text on one side, an icon card on the other. */
const props = defineProps<{ feature: FeatureItem; reverse?: boolean }>();

const parts = computed(() => splitBody(props.feature.body));
</script>

<template>
    <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
        <div :class="reverse ? 'lg:order-2' : ''">
            <p
                v-if="feature.eyebrow"
                class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/55"
            >
                {{ feature.eyebrow }}
            </p>
            <h2
                class="mt-4 text-4xl font-black uppercase leading-none tracking-[-0.06em] text-[#073b2a] sm:text-5xl"
            >
                {{ feature.title }}
            </h2>
            <p
                v-for="(para, i) in parts.paragraphs"
                :key="i"
                class="mt-6 text-base leading-7 text-[#073b2a]/65"
            >
                {{ para }}
            </p>
            <ul v-if="parts.bullets.length" class="mt-6 grid gap-3">
                <li
                    v-for="bullet in parts.bullets"
                    :key="bullet"
                    class="flex items-start gap-3 text-sm font-bold leading-6 text-[#073b2a]"
                >
                    <span
                        class="mt-0.5 flex size-5 shrink-0 items-center justify-center rounded-full bg-[#e4bc19]"
                    >
                        <Check class="size-3" />
                    </span>
                    {{ bullet }}
                </li>
            </ul>
        </div>
        <div
            class="relative flex min-h-64 items-center justify-center overflow-hidden rounded-3xl bg-[#073b2a] p-10 text-[#f7edcf]"
            :class="reverse ? 'lg:order-1' : ''"
        >
            <span
                class="absolute -right-10 -top-10 size-44 rounded-full bg-[#e4bc19]/15"
            />
            <span
                class="absolute -bottom-12 -left-8 size-40 rounded-full bg-[#e4bc19]/10"
            />
            <div class="relative text-center">
                <span
                    class="mx-auto flex size-24 items-center justify-center rounded-full bg-[#e4bc19] text-[#073b2a]"
                >
                    <FeatureIcon :name="feature.icon" class="size-11" />
                </span>
                <p
                    v-if="feature.eyebrow"
                    class="mt-6 text-xs font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
                >
                    {{ feature.eyebrow }}
                </p>
            </div>
        </div>
    </div>
</template>
