<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import type { SiteSettings } from '@/types';

const page = usePage<{ site: SiteSettings }>();
</script>

<template>
    <footer class="bg-[#073b2a] text-[#f7edcf]">
        <div
            class="mx-auto flex w-full max-w-7xl flex-col gap-5 px-6 py-8 text-xs uppercase tracking-[0.18em] text-[#f7edcf]/55 sm:flex-row sm:items-center sm:justify-between sm:px-10 lg:px-14"
        >
            <span>{{ page.props.site.footer_name }}</span>
            <span class="flex items-center gap-5">
                <div
                    class="flex flex-col items-center justify-between space-x-3 space-y-4 md:flex-row"
                >
                    <img
                        alt="Malawi Gaming &amp; Lotteries Authority"
                        class="order-2 h-9 w-9 shrink-0 object-contain md:order-1"
                        src="/images/magla-logo.png"
                    />
                    <p
                        class="order-1 flex items-center gap-2 text-[#e4bc19] md:order-2"
                    >
                        Regulated by MAGLA
                    </p>
                </div>
                <span>Est. {{ page.props.site.est_year }}</span>
            </span>
        </div>
        <div class="w-full overflow-hidden">
            <span
                class="whitespace-nowrap text-6xl font-extrabold leading-none text-gray-200 opacity-60 md:text-[246px]"
            >
                RADIO ENTERTAINMENT LIMITED
            </span>
        </div>
    </footer>
</template>
