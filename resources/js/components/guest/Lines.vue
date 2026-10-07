<script setup lang="ts">
/** Renders text with each newline as a line break (CMS multi-line titles). */
defineProps<{ text: string }>();
</script>

<template>
    <template v-for="(line, i) in text.split('\n')" :key="i">
        <br v-if="i > 0" />{{ line }}
    </template>
</template>
