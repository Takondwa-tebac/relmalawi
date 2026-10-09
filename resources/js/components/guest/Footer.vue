<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import type { SiteSettings } from '@/types';

const page = usePage<
    { site: SiteSettings } & Partial<{
        activePaths: string[];
        contactEnabled: boolean;
    }>
>();

const year = new Date().getFullYear();

const isActive = (href: string) =>
    !page.props.activePaths || page.props.activePaths.includes(href);

const contactEnabled = computed(() => page.props.contactEnabled !== false);

const allColumns: { title: string; links: [string, string][] }[] = [
    {
        title: 'Explore',
        links: [
            ['About', '/about'],
            ['How it works', '/how-it-works'],
            ['Our raffles', '/raffles'],
            ['Technology', '/technology'],
            ['Regulation', '/regulation'],
        ],
    },
    {
        title: 'Partners',
        links: [
            ['Partnerships', '/partnerships'],
            ['People', '/people'],
            ['Contact', '/contact'],
        ],
    },
    {
        title: 'Support',
        links: [
            ['FAQ', '/faq'],
            ['Contact', '/contact'],
        ],
    },
];

const columns = computed(() =>
    allColumns
        .map((column) => ({
            ...column,
            links: column.links.filter(
                ([, href]) =>
                    isActive(href) &&
                    (href !== '/contact' || contactEnabled.value),
            ),
        }))
        .filter((column) => column.links.length > 0),
);
</script>

<template>
    <footer class="bg-[#073b2a] text-[#f7edcf]">
        <div
            class="mx-auto grid w-full max-w-7xl gap-12 px-6 py-14 sm:px-10 lg:grid-cols-[1.4fr_2fr] lg:px-14"
        >
            <div>
                <Link href="/" aria-label="REL Radio home">
                    <AppLogoIcon class="h-auto w-28" />
                </Link>
                <p class="mt-5 max-w-sm text-sm leading-7 text-[#f7edcf]/60">
                    A licensed digital gaming operator bringing raffles to
                    Malawi through radio and television.
                </p>
                <a
                    v-if="contactEnabled"
                    :href="`mailto:${page.props.site.contact_email}`"
                    class="mt-5 inline-block text-sm font-bold text-[#e4bc19] underline-offset-4 hover:underline"
                >
                    {{ page.props.site.contact_email }}
                </a>
                <div class="mt-6 flex items-center gap-3">
                    <img
                        alt="Malawi Gaming &amp; Lotteries Authority"
                        class="size-10 shrink-0 object-contain"
                        src="/images/magla-logo.png"
                    />
                    <p
                        class="text-xs font-bold tracking-[0.18em] text-[#e4bc19] uppercase"
                    >
                        Regulated by MAGLA
                    </p>
                </div>
            </div>
            <nav
                class="grid grid-cols-2 gap-8 sm:grid-cols-3"
                aria-label="Footer navigation"
            >
                <div v-for="column in columns" :key="column.title">
                    <h2
                        class="text-xs font-bold tracking-[0.2em] text-[#f7edcf]/45 uppercase"
                    >
                        {{ column.title }}
                    </h2>
                    <ul class="mt-5 space-y-3">
                        <li v-for="[label, href] in column.links" :key="label">
                            <Link
                                :href="href"
                                class="text-sm text-[#f7edcf]/80 transition-colors hover:text-[#e4bc19]"
                            >
                                {{ label }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="border-t border-[#f7edcf]/10">
            <div
                class="mx-auto flex w-full max-w-7xl flex-col gap-3 px-6 py-6 text-xs tracking-[0.14em] text-[#f7edcf]/55 uppercase sm:flex-row sm:items-center sm:justify-between sm:px-10 lg:px-14"
            >
                <span>&copy; {{ year }} {{ page.props.site.footer_name }}</span>
                <span>Est. {{ page.props.site.est_year }}</span>
            </div>
        </div>
        <div class="w-full overflow-hidden" aria-hidden="true">
            <span
                class="block whitespace-nowrap text-6xl font-extrabold leading-none text-[#f7edcf] opacity-[0.07] md:text-[246px]"
            >
                RADIO ENTERTAINMENT LIMITED
            </span>
        </div>
    </footer>
</template>
