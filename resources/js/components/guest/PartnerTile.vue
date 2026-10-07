<script setup lang="ts">
import { computed } from 'vue';
import type { PartnerData } from '@/types/people';

const props = defineProps<{ partner: PartnerData }>();

const initials = computed(() =>
    props.partner.name
        .split(' ')
        .map((word) => word[0])
        .join('')
        .slice(0, 3),
);
</script>

<template>
    <component
        :is="partner.website_url ? 'a' : 'div'"
        :href="partner.website_url ?? undefined"
        :target="partner.website_url ? '_blank' : undefined"
        :rel="partner.website_url ? 'noopener noreferrer' : undefined"
        class="flex min-h-32 flex-col justify-between rounded-2xl border border-[#073b2a]/10 bg-[#f7edcf] p-5"
    >
        <img
            v-if="partner.logo_url"
            :src="partner.logo_url"
            :alt="partner.name"
            class="size-11 rounded-xl bg-white object-contain p-1"
        />
        <div
            v-else
            class="flex size-11 items-center justify-center rounded-xl bg-[#073b2a] text-xs font-black text-[#e4bc19]"
        >
            {{ initials }}
        </div>
        <div>
            <h3
                class="mt-5 text-sm font-black uppercase tracking-[-0.02em]"
            >
                {{ partner.name }}
            </h3>
            <p
                class="mt-1 text-[10px] font-bold uppercase tracking-[0.14em] text-[#073b2a]/45"
            >
                {{ partner.type }}
            </p>
        </div>
    </component>
</template>
