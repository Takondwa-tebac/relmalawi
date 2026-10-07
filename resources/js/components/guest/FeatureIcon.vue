<script setup lang="ts">
import {
    BarChart3,
    CheckCircle2,
    Crown,
    Eye,
    FileCheck2,
    Gamepad2,
    Gift,
    LockKeyhole,
    Radio,
    Scale,
    ShieldCheck,
    Shuffle,
} from '@lucide/vue';
import type { Component } from 'vue';
import { computed } from 'vue';
import type { FeatureIconKey } from '@/types/features';

const props = defineProps<{ name: string | null | undefined }>();

/** Keys mirror App\Enums\FeatureIcon. */
const registry: Record<FeatureIconKey, Component> = {
    radio: Radio,
    gamepad: Gamepad2,
    'shield-check': ShieldCheck,
    shuffle: Shuffle,
    'bar-chart': BarChart3,
    lock: LockKeyhole,
    eye: Eye,
    crown: Crown,
    gift: Gift,
    'file-check': FileCheck2,
    scale: Scale,
    'check-circle': CheckCircle2,
};

const icon = computed(() =>
    props.name ? (registry[props.name as FeatureIconKey] ?? null) : null,
);
</script>

<template>
    <component :is="icon" v-if="icon" />
</template>
