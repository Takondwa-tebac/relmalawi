<script setup>
const props = {
    value: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
};
</script>

<template>
    <div className="border-l border-[#e4bc19]/50 pl-5">
        <div className="text-4xl font-black tracking-[-0.06em] text-[#e4bc19]">
            {{ value }}
        </div>
        <div
            className="mt-2 text-[10px] font-bold uppercase tracking-[0.18em] text-[#f7edcf]/50"
        >
            {{ label }}
        </div>
    </div>
</template>
