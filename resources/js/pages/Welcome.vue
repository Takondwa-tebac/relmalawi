<script setup lang="ts">
import GuestLayout from "@/layouts/GuestLayout.vue";
import StatCard from "@/components/guest/StatsCard.vue";
import CampaignShowcase from "@/components/guest/CampaignShowCase.vue";
import { Head, Link } from "@inertiajs/vue3";

defineOptions({
    layout: GuestLayout,
    title: "Welcome",
});
</script>

<template>
    <Head title="REL">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <!-- <div class="w-full h-screen flex justify-items-center">

        <div class="m-auto text-center">
            <h1 class="text-5xl font-bold text-[#073b2a]">Welcome to REL</h1>
            <p class="mt-4 text-lg text-[#073b2a]">
                Malawi's next media games.
            </p>
            <div class="mt-6 flex justify-center gap-4">
                <Link
                    href="{login()}"
                    class="rounded-full bg-[#e4bc19] px-6 py-3 text-sm font-semibold uppercase tracking-wider text-[#073b2a] transition-colors hover:bg-[#073b2a] hover:text-[#e4bc19]"
                >
                    Login
                </Link>
                <Link
                    href="{dashboard()}"
                    class="rounded-full border border-[#e4bc19] px-6 py-3 text-sm font-semibold uppercase tracking-wider text-[#e4bc19] transition-colors hover:bg-[#e4bc19] hover:text-[#073b2a]"
                >
                    Dashboard
                </Link>
            </div>
        </div>
    </div> -->

    <section className="bg-[#073b2a]">
        <div
            className="mx-auto grid min-h-[calc(100vh-220px)] w-full max-w-7xl items-center gap-12 px-6 py-16 sm:px-10 lg:grid-cols-[1.05fr_.95fr] lg:px-14"
        >
            <div>
                <div
                    className="mb-8 flex items-center gap-3 text-xs font-bold uppercase tracking-[0.25em] text-[#e4bc19]"
                >
                    <span className="h-1 w-10 bg-[#e4bc19]"> Who we are</span>
                </div>
                <h1
                    className="max-w-3xl text-[clamp(4rem,10vw,8.5rem)] font-black uppercase leading-[0.82] tracking-[-0.08em] text-[#f7edcf]"
                >
                    Umoja<span className="block text-[#e4bc19]"
                        >Promotions.</span
                    >
                    <span className="block">Entertainment Media Games .</span>
                </h1>
                <p
                    className="mt-9 max-w-xl text-lg leading-8 text-[#f7edcf]/70"
                >
                    Radio Entertainment Limited is a licensed digital gaming
                    operator using innovative technology to expand the
                    addressable market for gaming in Malawi.
                </p>
                <p
                    className="mt-4 max-w-xl text-base leading-7 text-[#f7edcf]/55"
                >
                    We work with radio stations and media partners to bring
                    trusted gaming products to new audiences through the reach
                    and influence of broadcast media.
                </p>
                <div className="mt-10 flex flex-wrap gap-4">
                    <Link
                        href="/about"
                        className="group inline-flex items-center gap-3 rounded-full bg-[#e4bc19] px-5 py-3 text-sm font-bold uppercase tracking-[0.12em] text-[#073b2a]"
                    >
                        Discover REL <ArrowUpRight className="size-4" />
                    </Link>
                    <Link
                        href="/contact"
                        className="inline-flex items-center rounded-full border border-[#f7edcf]/25 px-5 py-3 text-sm uppercase tracking-[0.12em] text-[#f7edcf]/75"
                    >
                        Work with us
                    </Link>
                </div>
                <div className="mt-16 flex gap-10">
                    <StatCard value="40" label="Dynamic codes" />
                    <StatCard value="01" label="New platform" />
                </div>
            </div>
            <CampaignShowcase />
        </div>
    </section>
</template>
