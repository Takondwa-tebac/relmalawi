<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import CtaPair from '@/components/guest/CtaPair.vue';
import FeatureCard from '@/components/guest/FeatureCard.vue';
import FeatureIcon from '@/components/guest/FeatureIcon.vue';
import FeatureRow from '@/components/guest/FeatureRow.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import SectionHeading from '@/components/guest/SectionHeading.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { FeaturesByGroup } from '@/types/features';
import type { TeamMemberData } from '@/types/people';

defineOptions({ layout: GuestLayout });

const props = defineProps<{
    page: PageIntroData;
    features: FeaturesByGroup;
    team?: TeamMemberData[];
}>();

const story = computed(() => props.features['story']?.[0]);
const role = computed(() => props.features['role']?.[0]);
const stats = computed(() => props.features['stats'] ?? []);
const servicesIntro = computed(() => props.features['servicesIntro']?.[0]);
const services = computed(() => props.features['services'] ?? []);
const stepsIntro = computed(() => props.features['stepsIntro']?.[0]);
const steps = computed(() => props.features['steps'] ?? []);
const guides = computed(() => props.features['guides']?.[0]);
const pillars = computed(() => props.features['pillars'] ?? []);
const teamBlock = computed(() => props.features['team']?.[0]);
const cta = computed(() => props.features['cta']?.[0]);
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
        </div>
    </section>
    <section v-if="story" class="bg-white/60 px-5 py-16 sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <FeatureRow :feature="story" />
        </div>
    </section>
    <section v-if="role || stats.length" class="bg-[#f7edcf] px-5 py-16 sm:px-10 lg:py-24">
        <div class="mx-auto grid w-full max-w-7xl gap-6 lg:grid-cols-[1.1fr_.9fr]">
            <div
                v-if="role"
                class="rounded-3xl bg-[#073b2a] p-8 text-[#f7edcf] sm:p-12"
            >
                <p
                    v-if="role.eyebrow"
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
                >
                    {{ role.eyebrow }}
                </p>
                <h2
                    class="mt-5 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ role.title }}
                </h2>
                <p
                    v-if="role.body"
                    class="mt-7 max-w-xl text-base leading-7 text-[#f7edcf]/65"
                >
                    {{ role.body }}
                </p>
            </div>
            <div class="grid gap-4">
                <div
                    v-for="(stat, i) in stats"
                    :key="stat.id"
                    class="rounded-3xl p-7"
                    :class="
                        i === 0
                            ? 'bg-[#e4bc19]'
                            : 'border border-[#073b2a]/10 bg-white/60'
                    "
                >
                    <strong class="text-5xl font-black">{{ stat.title }}</strong>
                    <p class="mt-6 text-sm font-bold uppercase leading-6">
                        {{ stat.body }}
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section v-if="services.length" class="bg-white/60 px-5 py-16 sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <SectionHeading v-if="servicesIntro" :block="servicesIntro" />
            <div class="mt-10 grid gap-5 lg:grid-cols-3">
                <FeatureCard
                    v-for="service in services"
                    :key="service.id"
                    :feature="service"
                    variant="capability"
                />
            </div>
        </div>
    </section>
    <section v-if="steps.length" class="bg-[#073b2a] px-5 py-16 text-[#f7edcf] sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <SectionHeading v-if="stepsIntro" :block="stepsIntro" dark />
            <ol class="mt-10 grid gap-5 lg:grid-cols-3">
                <li
                    v-for="(step, i) in steps"
                    :key="step.id"
                    class="rounded-3xl border border-[#f7edcf]/15 p-7"
                >
                    <div class="flex items-center justify-between">
                        <FeatureIcon :name="step.icon" class="size-8 text-[#e4bc19]" />
                        <span class="text-sm font-black text-[#f7edcf]/35">0{{ i + 1 }}</span>
                    </div>
                    <h3 class="mt-10 text-2xl font-black uppercase tracking-[-0.04em]">
                        {{ step.title }}
                    </h3>
                    <p v-if="step.body" class="mt-4 text-sm leading-7 text-[#f7edcf]/65">
                        {{ step.body }}
                    </p>
                </li>
            </ol>
        </div>
    </section>
    <section v-if="pillars.length" class="bg-[#f7edcf] px-5 py-16 sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/50">
                {{ guides?.title }}
            </p>
            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                <FeatureCard
                    v-for="pillar in pillars"
                    :key="pillar.id"
                    :feature="pillar"
                    variant="pillar"
                />
            </div>
        </div>
    </section>
    <section
        v-if="teamBlock && team && team.length"
        class="bg-white/60 px-5 py-16 sm:px-10 lg:py-24"
    >
        <div class="mx-auto w-full max-w-7xl">
            <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
                <SectionHeading :block="teamBlock" />
                <Link
                    v-if="teamBlock.meta.button_url"
                    :href="String(teamBlock.meta.button_url)"
                    class="inline-flex w-fit items-center rounded-full bg-[#073b2a] px-5 py-3 text-xs font-bold uppercase tracking-[0.14em] text-[#f7edcf]"
                >
                    {{ teamBlock.meta.button_label ?? 'Meet the team' }}
                </Link>
            </div>
            <ul class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <li
                    v-for="member in team"
                    :key="member.id"
                    class="flex items-center gap-4 rounded-3xl bg-[#f7edcf] p-4"
                >
                    <img
                        v-if="member.photo_url"
                        :src="member.photo_url"
                        :alt="member.name"
                        class="size-20 shrink-0 rounded-2xl object-cover"
                    />
                    <div
                        v-else
                        class="size-20 shrink-0 rounded-2xl bg-[#073b2a]"
                    />
                    <div>
                        <p class="text-lg font-black uppercase tracking-[-0.04em]">
                            {{ member.name }}
                        </p>
                        <p
                            class="mt-1 text-[10px] font-bold uppercase tracking-[0.15em] text-[#073b2a]/55"
                        >
                            {{ member.role }}
                        </p>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <CtaPair v-if="cta" :block="cta" />
</template>
