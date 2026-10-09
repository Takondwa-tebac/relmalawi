<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronRight, Menu, X } from '@lucide/vue';
import {
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogOverlay,
    DialogPortal,
    DialogTitle,
} from 'reka-ui';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Sheet, SheetTrigger } from '@/components/ui/sheet';
import type { SiteNavItem, SiteSettings } from '@/types';

const fallbackNav: SiteNavItem[] = [
    { slug: 'about', label: 'About', href: '/about' },
    { slug: 'how-it-works', label: 'How it works', href: '/how-it-works' },
    { slug: 'raffles', label: 'Our raffles', href: '/raffles' },
    { slug: 'partnerships', label: 'Partnerships', href: '/partnerships' },
    { slug: 'technology', label: 'Technology', href: '/technology' },
    { slug: 'people', label: 'People', href: '/people' },
    { slug: 'regulation', label: 'Regulation', href: '/regulation' },
    { slug: 'faq', label: 'FAQ', href: '/faq' },
];

const page = usePage<
    { site: SiteSettings } & Partial<{
        nav: SiteNavItem[];
        activePaths: string[];
        contactEnabled: boolean;
        previewingInactive: boolean;
    }>
>();

const path = computed(() => page.url.split('?')[0]);
const navItems = computed(() => page.props.nav ?? fallbackNav);
const contactEnabled = computed(() => page.props.contactEnabled !== false);
const aboutActive = computed(
    () => !page.props.activePaths || page.props.activePaths.includes('/about'),
);
const previewing = computed(() => page.props.previewingInactive === true);

const open = ref(false);

watch(path, () => {
    open.value = false;
});

let mql: MediaQueryList | null = null;
const onChange = (e: MediaQueryListEvent) => {
    if (e.matches) {
        open.value = false;
    }
};

onMounted(() => {
    mql = window.matchMedia('(min-width: 768px)');
    mql.addEventListener('change', onChange);
});

onBeforeUnmount(() => {
    mql?.removeEventListener('change', onChange);
});
</script>

<template>
    <div
        v-if="previewing"
        role="status"
        class="bg-red-700 px-6 py-1.5 text-center text-[11px] font-bold uppercase tracking-[0.14em] text-white sm:px-10"
    >
        Preview: this page is switched off and is only visible to signed-in
        staff.
    </div>
    <div
        class="bg-[#e4bc19] px-6 py-2 text-center text-[11px] font-bold uppercase tracking-[0.14em] text-[#073b2a] sm:px-10"
    >
        {{ page.props.site.banner_text }}
        <Link
            v-if="aboutActive"
            href="/about"
            class="ml-2 underline underline-offset-4 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#073b2a]"
        >
            Read our story
        </Link>
    </div>
    <header class="bg-[#073b2a] text-[#f7edcf]">
        <div
            class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-5 sm:px-10 lg:px-14"
        >
            <Link
                href="/"
                aria-label="REL Radio home"
                class="rounded-sm focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#e4bc19]"
            >
                <AppLogoIcon class="h-auto w-28" />
            </Link>
            <div class="flex items-center gap-3">
                <Link
                    v-if="contactEnabled"
                    href="/contact"
                    class="group inline-flex items-center gap-2 rounded-full bg-[#e4bc19] px-4 py-2 text-[11px] font-bold uppercase tracking-[0.15em] text-[#073b2a] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#f7edcf]"
                >
                    Connect
                </Link>
                <Sheet v-model:open="open">
                    <SheetTrigger as-child>
                        <button
                            type="button"
                            aria-label="Open menu"
                            class="inline-flex size-11 items-center justify-center rounded-md text-[#f7edcf] transition-colors hover:text-[#e4bc19] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e4bc19] md:hidden"
                        >
                            <Menu class="size-6" aria-hidden="true" />
                        </button>
                    </SheetTrigger>
                    <DialogPortal>
                        <DialogOverlay
                            class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:animate-in data-[state=open]:fade-in-0 md:hidden"
                        />
                        <DialogContent
                            class="fixed inset-y-0 right-0 z-50 flex h-full w-[85vw] max-w-[22rem] flex-col bg-[#073b2a] text-[#f7edcf] shadow-2xl ease-in-out data-[state=closed]:animate-out data-[state=closed]:duration-300 data-[state=closed]:slide-out-to-right data-[state=open]:animate-in data-[state=open]:duration-500 data-[state=open]:slide-in-from-right md:hidden"
                        >
                            <DialogTitle class="sr-only">
                                Site menu
                            </DialogTitle>
                            <DialogDescription class="sr-only">
                                Navigate to a page on the REL Malawi website.
                            </DialogDescription>

                            <div
                                class="flex items-center justify-between border-b border-[#f7edcf]/10 px-6 py-5"
                            >
                                <Link
                                    href="/"
                                    aria-label="REL Radio home"
                                    class="rounded-sm focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#e4bc19]"
                                >
                                    <AppLogoIcon class="h-auto w-24" />
                                </Link>
                                <DialogClose
                                    aria-label="Close menu"
                                    class="-mr-2 inline-flex size-11 items-center justify-center rounded-md text-[#f7edcf] transition-colors hover:text-[#e4bc19] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e4bc19]"
                                >
                                    <X class="size-6" aria-hidden="true" />
                                </DialogClose>
                            </div>

                            <nav
                                class="flex-1 overflow-y-auto"
                                aria-label="Mobile navigation"
                            >
                                <ul class="divide-y divide-[#f7edcf]/10">
                                    <li v-for="item in navItems" :key="item.href">
                                        <Link
                                            :href="item.href"
                                            :aria-current="
                                                path === item.href
                                                    ? 'page'
                                                    : undefined
                                            "
                                            :class="[
                                                'flex min-h-14 items-center justify-between border-l-4 px-6 py-4 text-lg font-bold uppercase tracking-[0.12em] transition-colors focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-[#e4bc19]',
                                                path === item.href
                                                    ? 'border-[#e4bc19] bg-[#f7edcf]/5 text-[#e4bc19]'
                                                    : 'border-transparent text-[#f7edcf] hover:text-[#e4bc19]',
                                            ]"
                                            @click="open = false"
                                        >
                                            {{ item.label }}
                                            <ChevronRight
                                                class="size-5 shrink-0 opacity-60"
                                                aria-hidden="true"
                                            />
                                        </Link>
                                    </li>
                                </ul>
                            </nav>

                            <div
                                class="space-y-4 border-t border-[#f7edcf]/10 px-6 py-6"
                            >
                                <Link
                                    v-if="contactEnabled"
                                    href="/contact"
                                    class="flex min-h-12 w-full items-center justify-center rounded-full bg-[#e4bc19] px-6 py-3 text-sm font-bold uppercase tracking-[0.15em] text-[#073b2a] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#f7edcf]"
                                    @click="open = false"
                                >
                                    Connect
                                </Link>
                                <a
                                    v-if="page.props.site.contact_email"
                                    :href="`mailto:${page.props.site.contact_email}`"
                                    class="block text-center text-sm font-bold text-[#e4bc19] underline-offset-4 hover:underline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#e4bc19]"
                                >
                                    {{ page.props.site.contact_email }}
                                </a>
                                <div
                                    class="flex items-center justify-center gap-3"
                                >
                                    <img
                                        alt="Malawi Gaming &amp; Lotteries Authority"
                                        class="size-9 shrink-0 object-contain"
                                        src="/images/magla-logo.png"
                                    />
                                    <p
                                        class="text-[11px] font-bold tracking-[0.18em] text-[#e4bc19] uppercase"
                                    >
                                        Regulated by MAGLA
                                    </p>
                                </div>
                            </div>
                        </DialogContent>
                    </DialogPortal>
                </Sheet>
            </div>
        </div>
        <nav
            class="hidden border-t border-[#f7edcf]/10 md:block"
            aria-label="Main navigation"
        >
            <div
                class="mx-auto flex w-full max-w-7xl items-center gap-7 overflow-x-auto px-6 sm:px-10 lg:px-14"
            >
                <Link
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    :aria-current="path === item.href ? 'page' : undefined"
                    :class="[
                        'whitespace-nowrap border-b-4 py-4 text-[11px] font-bold uppercase tracking-[0.18em] transition-colors focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-[#e4bc19]',
                        path === item.href
                            ? 'border-[#e4bc19] text-[#f7edcf]'
                            : 'border-transparent text-[#f7edcf]/60 hover:text-[#e4bc19]',
                    ]"
                >
                    {{ item.label }}
                </Link>
            </div>
        </nav>
    </header>
</template>
