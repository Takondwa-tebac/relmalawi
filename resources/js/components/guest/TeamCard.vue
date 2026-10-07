<script setup lang="ts">
import type { TeamMemberData } from '@/types/people';

defineProps<{ member: TeamMemberData }>();
</script>

<template>
    <article class="group overflow-hidden rounded-3xl bg-white/60 shadow-sm">
        <div class="aspect-[4/5] overflow-hidden bg-[#073b2a]">
            <img
                v-if="member.photo_url"
                :src="member.photo_url"
                :alt="`${member.name}, ${member.role}`"
                class="size-full object-cover grayscale transition duration-500 group-hover:scale-105 group-hover:grayscale-0"
            />
        </div>
        <div class="p-6">
            <p
                class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#073b2a]/45"
            >
                {{ member.role }}
            </p>
            <h2
                class="mt-2 text-xl font-black uppercase tracking-[-0.04em]"
            >
                {{ member.name }}
            </h2>
            <p
                v-if="member.bio"
                class="mt-3 text-sm leading-6 text-[#073b2a]/65"
            >
                {{ member.bio }}
            </p>
        </div>
    </article>
</template>
