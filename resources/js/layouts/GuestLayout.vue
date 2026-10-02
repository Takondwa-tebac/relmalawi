<script setup>
import { Navbar } from "@/components/guest/navbar";
</script>

<template>
    <div
        class="relative min-h-screen overflow-hidden bg-[#f7edcf] text-[#073b2a]"
    >
        <div class="relative z-10">
            <NavBar />
        </div>
        <slot />
    </div>
</template>
