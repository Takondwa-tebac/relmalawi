<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import CtaBand from '@/components/guest/CtaBand.vue';
import FeatureCard from '@/components/guest/FeatureCard.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { FeaturesByGroup } from '@/types/features';

defineOptions({ layout: GuestLayout });

const props = defineProps<{ page: PageIntroData; features: FeaturesByGroup }>();

const role = computed(() => props.features['role']?.[0]);
const stats = computed(() => props.features['stats'] ?? []);
const guides = computed(() => props.features['guides']?.[0]);
const pillars = computed(() => props.features['pillars'] ?? []);
const cta = computed(() => props.features['cta']?.[0]);
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
            <div v-if="role || stats.length" class="mt-16 grid gap-6 lg:grid-cols-[1.1fr_.9fr]">
                <div
                    v-if="role"
                    class="rounded-3xl bg-[#073b2a] p-8 text-[#f7edcf] sm:p-12"
                >
                    <p
                        v-if="role.eyebrow"
                        class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
                    >
                        {{ role.eyebrow }}
                    </p>
                    <h2
                        class="mt-5 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                    >
                        {{ role.title }}
                    </h2>
                    <p
                        v-if="role.body"
                        class="mt-7 max-w-xl text-base leading-7 text-[#f7edcf]/65"
                    >
                        {{ role.body }}
                    </p>
                </div>
                <div class="grid gap-4">
                    <div
                        v-for="(stat, i) in stats"
                        :key="stat.id"
                        class="rounded-3xl p-7"
                        :class="
                            i === 0
                                ? 'bg-[#e4bc19]'
                                : 'border border-[#073b2a]/10 bg-white/60'
                        "
                    >
                        <strong class="text-5xl font-black">{{ stat.title }}</strong>
                        <p class="mt-8 text-sm font-bold uppercase leading-6">
                            {{ stat.body }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section v-if="pillars.length" class="bg-white/60 px-5 py-16 sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/50">
                {{ guides?.title }}
            </p>
            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                <FeatureCard
                    v-for="pillar in pillars"
                    :key="pillar.id"
                    :feature="pillar"
                    variant="pillar"
                />
            </div>
        </div>
    </section>
    <CtaBand v-if="cta" :block="cta" />
</template>
