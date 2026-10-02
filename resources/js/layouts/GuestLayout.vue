<script setup>
import NavBar from "@/components/guest/NavBar.vue";
import Footer from "@/components/guest/Footer.vue";
</script>

<template>
    <div
        class="relative min-h-screen overflow-hidden bg-[#f7edcf] text-[#073b2a]"
    >
        <div class="relative z-10">
            <NavBar />
        </div>

        <slot />
        <Footer />
    </div>
</template>
