<script setup lang="ts">
import GuestLayout from "@layouts/GuestLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { dashboard, login } from "@/routes";

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

    <GuestLayout> </GuestLayout>
</template>
