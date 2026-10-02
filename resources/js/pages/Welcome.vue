<script setup lang="ts">
import GuestLayout from "@/layouts/GuestLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
// import { dashboard, login } from "@/routes";

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

    <GuestLayout>
        <div class="w-full h-screen flex justify-items-center">
            <div class="m-auto text-center">
                <h1 class="text-5xl font-bold text-[#073b2a]">
                    Welcome to REL
                </h1>
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
        </div>
    </GuestLayout>
</template>
