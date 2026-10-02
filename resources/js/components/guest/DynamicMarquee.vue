<script setup>
const codes = Array.from({ length: 31 }, (_, index) => `*4342*${index + 1}#`);
</script>

<template>
    <section
        aria-label="REL dynamic codes"
        class="relative overflow-hidden border-y border-[#073b2a]/10 bg-[#e4bc19] py-4 text-[#073b2a]"
    >
        <div
            class="pointer-events-none absolute inset-y-0 left-0 z-10 w-20 bg-gradient-to-r from-[#e4bc19] to-transparent blur-[2px]"
        />

        <div
            class="pointer-events-none absolute inset-y-0 right-0 z-10 w-20 bg-gradient-to-l from-[#e4bc19] to-transparent blur-[2px]"
        />

        <div
            class="flex w-max animate-[marquee_35s_linear_infinite] gap-8 whitespace-nowrap"
        >
            <span
                v-for="(code, index) in [...codes, ...codes]"
                :key="`${code}-${index}`"
                class="flex items-center gap-8 text-sm font-black uppercase tracking-[0.16em]"
            >
                <span>{{ code }}</span>

                <span class="text-[#073b2a]/35">•</span>
            </span>
        </div>
    </section>
</template>
