<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="rel-auth grid min-h-svh grid-rows-[auto_1fr] bg-[#f7edcf] text-[#073b2a] lg:grid-cols-[1.05fr_1fr] lg:grid-rows-1"
    >
        <!-- Brand panel: matches the public site's green hero -->
        <aside
            class="relative flex flex-col justify-between overflow-hidden bg-[#073b2a] p-6 text-[#f7edcf] sm:p-10 lg:p-14"
        >
            <Link :href="home()" aria-label="REL home" class="w-fit">
                <AppLogoIcon class="h-auto w-28 sm:w-32" />
            </Link>

            <div class="hidden py-16 lg:block">
                <div
                    class="mb-8 flex items-center gap-3 text-xs font-bold uppercase tracking-[0.25em] text-[#e4bc19]"
                >
                    <span class="h-1 w-10 bg-[#e4bc19]" />
                    Staff area
                </div>
                <p
                    class="max-w-md text-[clamp(3rem,6vw,5.5rem)] font-black uppercase leading-[0.85] tracking-[-0.07em]"
                >
                    Welcome
                    <span class="block text-[#e4bc19]">back.</span>
                </p>
                <p class="mt-8 max-w-sm text-base leading-7 text-[#f7edcf]/65">
                    Manage the campaigns, people, partners and messages behind
                    the REL website.
                </p>
            </div>

            <div
                class="hidden items-center gap-4 text-xs uppercase tracking-[0.18em] text-[#f7edcf]/55 lg:flex"
            >
                <img
                    src="/images/magla-logo.png"
                    alt="Malawi Gaming &amp; Lotteries Authority"
                    class="h-9 w-9 object-contain"
                />
                <span class="text-[#e4bc19]">Regulated by MAGLA</span>
            </div>

            <div
                class="pointer-events-none absolute -bottom-24 -right-24 hidden size-80 rounded-full border-[3rem] border-[#e4bc19]/10 lg:block"
            />
        </aside>

        <!-- Form panel -->
        <main class="flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-sm">
                <div class="mb-8 space-y-3">
                    <div
                        class="flex items-center gap-3 text-xs font-bold uppercase tracking-[0.25em] text-[#073b2a]/55"
                    >
                        <span class="h-1 w-10 bg-[#e4bc19]" />
                        Sign in
                    </div>
                    <h1
                        class="text-4xl font-black uppercase leading-[0.9] tracking-[-0.05em]"
                    >
                        {{ title }}
                    </h1>
                    <p class="text-sm leading-6 text-[#073b2a]/65">
                        {{ description }}
                    </p>
                </div>

                <div class="rounded-3xl bg-white/60 p-6 shadow-sm sm:p-8">
                    <slot />
                </div>

                <Link
                    :href="home()"
                    class="mt-6 inline-block text-xs font-bold uppercase tracking-[0.18em] text-[#073b2a]/60 transition-colors hover:text-[#073b2a]"
                >
                    &larr; Back to the website
                </Link>
            </div>
        </main>
    </div>
</template>
