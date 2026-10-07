<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import {
    ArrowsPointingOutIcon,
    ArrowUpRightIcon,
    XMarkIcon,
} from '@heroicons/vue/24/outline';
import { onBeforeUnmount, ref, watch } from 'vue';
import type { CampaignData } from '@/types/home';

defineProps<{ campaigns: CampaignData[] }>();

const selected = ref<CampaignData | null>(null);

const closeOnEscape = (event: KeyboardEvent) => {
    if (event.key === 'Escape') {
        selected.value = null;
    }
};

watch(selected, (value) => {
    if (value) {
        document.addEventListener('keydown', closeOnEscape);
        document.body.style.overflow = 'hidden';
    } else {
        document.removeEventListener('keydown', closeOnEscape);
        document.body.style.overflow = '';
    }
});

onBeforeUnmount(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = '';
});
</script>

<template>
    <div
        class="relative overflow-hidden rounded-[2rem] bg-[#e4bc19] p-5 text-[#073b2a] sm:p-7"
    >
        <div
            class="flex items-center justify-between text-[10px] font-bold uppercase tracking-[0.18em]"
        >
            <span>Campaigns we power</span>
            <span>Hover to pause</span>
        </div>

        <div
            class="group relative mt-5 h-[22rem] overflow-hidden rounded-2xl bg-[#073b2a]/10 sm:h-[28rem]"
        >
            <div
                v-if="campaigns.length"
                class="campaign-roll absolute inset-x-0 top-0 flex flex-col gap-3 p-3 group-hover:[animation-play-state:paused]"
            >
                <button
                    v-for="(campaign, index) in [...campaigns, ...campaigns]"
                    :key="`${campaign.id}-${index}`"
                    type="button"
                    class="group/card relative block h-56 w-full shrink-0 overflow-hidden rounded-xl text-left sm:h-72"
                    :aria-label="`Open ${campaign.title} image fullscreen`"
                    @click="selected = campaign"
                >
                    <img
                        v-if="campaign.thumb ?? campaign.image"
                        :src="(campaign.thumb ?? campaign.image) as string"
                        :alt="campaign.alt"
                        loading="lazy"
                        class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover/card:scale-105"
                    />
                    <span
                        class="absolute inset-0 bg-gradient-to-t from-[#073b2a]/75 via-transparent to-transparent"
                    />
                    <span
                        class="absolute inset-x-4 bottom-4 flex items-center justify-between text-xs font-bold uppercase tracking-[0.14em] text-[#f7edcf]"
                    >
                        <span>{{ campaign.title }}</span>
                        <ArrowsPointingOutIcon class="size-4" />
                    </span>
                </button>
            </div>

            <div
                class="pointer-events-none absolute inset-x-0 top-0 h-16 bg-gradient-to-b from-[#e4bc19] to-transparent"
            />
            <div
                class="pointer-events-none absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#e4bc19] to-transparent"
            />
        </div>

        <div class="mt-5 flex items-end justify-between gap-4">
            <div>
                <p
                    class="text-2xl leading-none font-black tracking-[-0.05em] uppercase"
                >
                    From airwave<br />
                    to entry.
                </p>
                <p
                    class="mt-2 max-w-[220px] text-xs leading-5 text-[#073b2a]/65"
                >
                    We turn partner programming into memorable, measurable
                    participation.
                </p>
            </div>

            <Link
                href="/partnerships"
                aria-label="View campaigns"
                class="flex size-11 shrink-0 items-center justify-center rounded-full bg-[#073b2a] text-[#e4bc19] transition-transform hover:rotate-45"
            >
                <ArrowUpRightIcon class="size-5" />
            </Link>
        </div>
    </div>

    <Teleport to="body">
        <div
            v-if="selected"
            class="fixed inset-0 z-50 flex items-center justify-center bg-[#073b2a]/95 p-5"
            role="dialog"
            aria-modal="true"
            :aria-label="`${selected.title} fullscreen image`"
            @click="selected = null"
        >
            <div class="relative h-full w-full max-w-5xl" @click.stop>
                <img
                    v-if="selected.image"
                    :src="selected.image"
                    :alt="selected.alt"
                    class="absolute inset-0 h-full w-full object-contain"
                />
                <p
                    class="absolute bottom-3 left-3 text-sm font-bold tracking-[0.14em] text-[#f7edcf] uppercase"
                >
                    {{ selected.title }}
                </p>
                <button
                    type="button"
                    aria-label="Close fullscreen image"
                    class="absolute top-0 right-0 flex size-11 items-center justify-center rounded-full bg-[#e4bc19] text-[#073b2a]"
                    @click="selected = null"
                >
                    <XMarkIcon class="size-5" />
                </button>
            </div>
        </div>
    </Teleport>
</template>
