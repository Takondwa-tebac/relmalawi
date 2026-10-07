<script setup lang="ts">
defineProps<{ value: string; label: string }>();
</script>

<template>
    <div class="border-l border-[#e4bc19]/50 pl-5">
        <div class="text-4xl font-black tracking-[-0.06em] text-[#e4bc19]">
            {{ value }}
        </div>
        <div
            class="mt-2 text-[10px] font-bold uppercase tracking-[0.18em] text-[#f7edcf]/50"
        >
            {{ label }}
        </div>
    </div>
</template>
