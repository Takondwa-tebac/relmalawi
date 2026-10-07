<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    Banknote,
    CheckCircle2,
    MessageSquare,
    Radio,
    Shuffle,
    Smartphone,
    Trophy,
    Wallet,
} from '@lucide/vue';
import type { Component } from 'vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { HowItWorksStepData } from '@/types/contact';

defineOptions({ layout: GuestLayout });

defineProps<{ page: PageIntroData; steps: HowItWorksStepData[] }>();

const icons: Record<string, Component> = {
    smartphone: Smartphone,
    wallet: Wallet,
    'message-square': MessageSquare,
    shuffle: Shuffle,
    trophy: Trophy,
    banknote: Banknote,
    radio: Radio,
    'check-circle': CheckCircle2,
};
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
            <ol class="mt-16 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <li
                    v-for="step in steps"
                    :key="step.id"
                    class="rounded-3xl bg-white/60 p-7 sm:p-9"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-5xl font-black text-[#e4bc19]">{{
                            step.number
                        }}</span>
                        <component
                            :is="icons[step.icon ?? '']"
                            v-if="step.icon && icons[step.icon]"
                            class="size-8 text-[#073b2a]"
                        />
                    </div>
                    <h2 class="mt-12 text-2xl font-black uppercase">
                        {{ step.title }}
                    </h2>
                    <p
                        v-if="step.body"
                        class="mt-3 max-w-sm text-sm leading-7 text-[#073b2a]/60"
                    >
                        {{ step.body }}
                    </p>
                </li>
            </ol>
        </div>
    </section>
    <section
        class="bg-[#073b2a] px-5 py-16 text-[#f7edcf] sm:px-10 lg:px-14 lg:py-24"
    >
        <div
            class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-8 lg:flex-row lg:items-end"
        >
            <h2
                class="max-w-3xl text-4xl font-black uppercase leading-none tracking-[-0.05em] sm:text-6xl"
            >
                Questions? Let's talk.
            </h2>
            <Link
                href="/contact"
                class="rounded-xl bg-[#e4bc19] px-6 py-3 text-sm font-bold uppercase tracking-[0.1em] text-[#073b2a] transition-colors hover:bg-[#f0cb3a]"
            >
                Get in touch
            </Link>
        </div>
    </section>
</template>
