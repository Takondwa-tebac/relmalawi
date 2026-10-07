<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import type { SiteSettings } from '@/types';

const navItems = [
    ['About', '/about'],
    ['How it works', '/how-it-works'],
    ['Our raffles', '/raffles'],
    ['Partnerships', '/partnerships'],
    ['Technology', '/technology'],
    ['People', '/people'],
    ['Regulation', '/regulation'],
] as const;

const page = usePage<{ site: SiteSettings }>();
const path = computed(() => page.url.split('?')[0]);
</script>

<template>
    <div
        class="bg-[#e4bc19] px-6 py-2 text-center text-[11px] font-bold uppercase tracking-[0.14em] text-[#073b2a] sm:px-10"
    >
        {{ page.props.site.banner_text }}
        <Link href="/about" class="ml-2 underline underline-offset-4">
            Read our story
        </Link>
    </div>
    <header class="bg-[#073b2a] text-[#f7edcf]">
        <div
            class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-5 sm:px-10 lg:px-14"
        >
            <Link href="/" aria-label="REL Radio home">
                <AppLogoIcon class="h-auto w-28" />
            </Link>
            <Link
                href="/contact"
                class="group inline-flex items-center gap-2 rounded-full bg-[#e4bc19] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.15em] text-[#073b2a]"
            >
                Connect
            </Link>
        </div>
        <nav class="border-t border-[#f7edcf]/10" aria-label="Main navigation">
            <div
                class="mx-auto flex w-full max-w-7xl items-center gap-7 overflow-x-auto px-6 sm:px-10 lg:px-14"
            >
                <Link
                    v-for="[label, href] in navItems"
                    :key="href"
                    :href="href"
                    :class="[
                        'whitespace-nowrap border-b-4 py-4 text-[11px] font-bold uppercase tracking-[0.18em] transition-colors',
                        path === href
                            ? 'border-[#e4bc19] text-[#f7edcf]'
                            : 'border-transparent text-[#f7edcf]/60 hover:text-[#e4bc19]',
                    ]"
                >
                    {{ label }}
                </Link>
            </div>
        </nav>
    </header>
</template>
