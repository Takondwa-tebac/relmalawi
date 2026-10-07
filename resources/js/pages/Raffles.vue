<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import FeatureCard from '@/components/guest/FeatureCard.vue';
import FeatureIcon from '@/components/guest/FeatureIcon.vue';
import Lines from '@/components/guest/Lines.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { FeaturesByGroup } from '@/types/features';

defineOptions({ layout: GuestLayout });

const props = defineProps<{ page: PageIntroData; features: FeaturesByGroup }>();

const formats = computed(() => props.features['formats'] ?? []);
const band = computed(() => props.features['band']?.[0]);
const cards = computed(() => props.features['cards'] ?? []);
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
            <div v-if="formats.length" class="mt-16 grid gap-5 lg:grid-cols-3">
                <FeatureCard
                    v-for="(format, i) in formats"
                    :key="format.id"
                    :feature="format"
                    :index="i"
                    variant="format"
                />
            </div>
        </div>
    </section>
    <section
        v-if="band || cards.length"
        class="bg-[#e4bc19] px-5 py-16 sm:px-10 lg:px-14 lg:py-24"
    >
        <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-[.8fr_1.2fr]">
            <div v-if="band">
                <p
                    v-if="band.eyebrow"
                    class="text-xs font-bold uppercase tracking-[0.2em]"
                >
                    {{ band.eyebrow }}
                </p>
                <h2
                    class="mt-4 text-5xl font-black uppercase leading-none tracking-[-0.06em]"
                >
                    <Lines :text="band.title" />
                </h2>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div
                    v-for="(card, i) in cards"
                    :key="card.id"
                    class="rounded-2xl p-6"
                    :class="
                        i === 0
                            ? 'bg-[#073b2a] text-[#f7edcf]'
                            : 'bg-white/45'
                    "
                >
                    <FeatureIcon
                        :name="card.icon"
                        class="size-7 text-[#e4bc19]"
                    />
                    <h3
                        class="text-xl font-black uppercase"
                        :class="card.icon ? 'mt-12' : ''"
                    >
                        {{ card.title }}
                    </h3>
                    <p
                        v-if="card.body"
                        class="text-sm leading-6"
                        :class="[
                            card.icon ? 'mt-3' : 'mt-12',
                            i === 0
                                ? 'text-[#f7edcf]/60'
                                : 'text-[#073b2a]/65',
                        ]"
                    >
                        {{ card.body }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
