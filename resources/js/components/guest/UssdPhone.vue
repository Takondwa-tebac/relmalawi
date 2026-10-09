<script setup lang="ts">
import { ChevronLeft, ChevronRight } from '@lucide/vue';
import { computed, ref } from 'vue';
import type { FeatureItem } from '@/types/features';

/** Stylised handset. Each feature row is one screen: title + body lines. Illustrative only. */
const props = defineProps<{ screens: FeatureItem[] }>();

const current = ref(0);
const screen = computed(() => props.screens[current.value]);
const bodyLines = computed(() =>
    (screen.value?.body ?? '').split('\n').filter((l) => l.trim() !== ''),
);
const isPin = computed(() => current.value === props.screens.length - 1);

const go = (delta: number) => {
    const n = props.screens.length;
    current.value = (current.value + delta + n) % n;
};
</script>

<template>
    <div v-if="screens.length" class="mx-auto w-full max-w-[19rem]">
        <div
            class="rounded-[2.5rem] border-4 border-[#f7edcf]/20 bg-[#021f16] p-3 shadow-2xl"
        >
            <div class="mx-auto mb-3 h-1.5 w-16 rounded-full bg-[#f7edcf]/20" />
            <div
                class="flex min-h-72 flex-col rounded-3xl bg-[#f7edcf] p-5 text-[#073b2a]"
                aria-live="polite"
            >
                <p
                    class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#073b2a]/50"
                >
                    {{ screen.title }}
                </p>
                <div class="mt-4 space-y-2 font-mono text-sm leading-6">
                    <p v-for="(line, i) in bodyLines" :key="i">{{ line }}</p>
                </div>
                <div
                    class="mt-auto flex items-center justify-between rounded-lg border border-[#073b2a]/25 bg-white/70 px-3 py-2 font-mono text-sm"
                >
                    <span>{{ isPin ? '••••' : '1' }}</span>
                    <span class="text-[10px] font-bold uppercase">Send</span>
                </div>
            </div>
        </div>
        <div class="mt-5 flex items-center justify-between">
            <button
                type="button"
                class="rounded-full border border-[#f7edcf]/30 p-2 text-[#f7edcf]"
                aria-label="Previous screen"
                @click="go(-1)"
            >
                <ChevronLeft class="size-4" />
            </button>
            <div class="flex gap-2">
                <button
                    v-for="(s, i) in screens"
                    :key="s.id"
                    type="button"
                    class="size-2.5 rounded-full transition-colors"
                    :class="i === current ? 'bg-[#e4bc19]' : 'bg-[#f7edcf]/30'"
                    :aria-label="`Show screen ${i + 1}: ${s.title}`"
                    @click="current = i"
                />
            </div>
            <button
                type="button"
                class="rounded-full border border-[#f7edcf]/30 p-2 text-[#f7edcf]"
                aria-label="Next screen"
                @click="go(1)"
            >
                <ChevronRight class="size-4" />
            </button>
        </div>
        <p
            class="mt-4 text-center text-[10px] font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
        >
            Illustrative
        </p>
    </div>
</template>
