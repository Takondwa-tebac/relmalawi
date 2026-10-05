<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import AppLogoIcon from "@/components/AppLogoIcon.vue";
import { HomeIcon, ArrowRightIcon, TrashIcon } from "@heroicons/vue/24/outline";

const navItems = [
    ["About", "/about"],
    ["How it works", "/how-it-works"],
    ["Our raffles", "/raffles"],
    // ["Partnerships", "/partnerships"],
    ["Technology", "/technology"],
    ["People", "/people"],
    ["Regulation", "/regulation"],
];
const page = usePage();
</script>
<template>
    <div
        className="bg-[#e4bc19] px-6 py-2 text-center text-[11px] font-bold uppercase tracking-[0.14em] text-[#073b2a] sm:px-10"
    >
        REL is building the infrastructure behind Malawi&apos;s next media
        Games.

        <Link href="/about" className="ml-2 underline underline-offset-4"
            >Read our story</Link
        >
    </div>
    <header className="bg-[#073b2a] text-[#f7edcf]">
        <div
            className="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-5 sm:px-10 lg:px-14"
        >
            <Link href="/" aria-label="REL Radio home"
                ><AppLogoIcon class="h-auto w-28"
            /></Link>
            <Link
                href="/contact"
                className="group inline-flex items-center gap-2 rounded-full bg-[#e4bc19] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.15em] text-[#073b2a]"
                >Connect
            </Link>
        </div>
        <nav
            className="border-t border-[#f7edcf]/10"
            aria-label="Main navigation"
        >
            <div
                className="mx-auto flex w-full max-w-7xl items-center gap-7 overflow-x-auto px-6 sm:px-10 lg:px-14"
            >
                <template
                    v-for="([label, href], index) in navItems"
                    :key="href"
                >
                    <Link
                        :href="href"
                        :class="[
                            'whitespace-nowrap border-b-4 py-4 text-[11px] font-bold uppercase tracking-[0.18em] transition-colors',
                            page?.url === href
                                ? 'border-[#e4bc19] text-[#f7edcf]'
                                : 'border-transparent text-[#f7edcf]/60 hover:text-[#e4bc19]',
                        ]"
                    >
                        {{ label }}
                    </Link>
                </template>
            </div>
        </nav>
    </header>
</template>
