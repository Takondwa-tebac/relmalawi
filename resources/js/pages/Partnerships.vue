<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ArrowUpRight } from '@lucide/vue';
import CtaBand from '@/components/guest/CtaBand.vue';
import FeatureIcon from '@/components/guest/FeatureIcon.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import PartnerTile from '@/components/guest/PartnerTile.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { FeaturesByGroup } from '@/types/features';
import type { PartnerData } from '@/types/people';

defineOptions({ layout: GuestLayout });

const props = defineProps<{
    page: PageIntroData;
    features: FeaturesByGroup;
    partners: PartnerData[];
    payment_partners: PartnerData[];
}>();

const group = (name: string) => props.features[name] ?? [];
const first = (name: string) => group(name)[0];
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />

    <!-- 1. Intro + two calls to action + offer cards -->
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
            <div v-if="group('hero').length" class="mt-10 flex flex-wrap gap-3">
                <a
                    v-for="(button, index) in group('hero')"
                    :key="button.id"
                    :href="String(button.meta.button_url ?? '/contact')"
                    class="inline-flex items-center gap-2 rounded-full px-6 py-3 text-xs font-bold uppercase tracking-[0.14em]"
                    :class="
                        index === 0
                            ? 'bg-[#073b2a] text-[#f7edcf]'
                            : 'border border-[#073b2a]/30 text-[#073b2a]'
                    "
                >
                    {{ button.title }} <ArrowUpRight class="size-4" />
                </a>
            </div>
            <div class="mt-16 grid gap-5 lg:grid-cols-3">
                <article
                    v-for="(offer, index) in group('offers')"
                    :key="offer.id"
                    class="rounded-3xl bg-[#073b2a] p-7 text-[#f7edcf] sm:p-9"
                >
                    <div class="flex items-center justify-between">
                        <FeatureIcon
                            :name="offer.icon"
                            class="size-9 text-[#e4bc19]"
                        />
                        <span class="text-sm font-black text-[#f7edcf]/35"
                            >0{{ index + 1 }}</span
                        >
                    </div>
                    <h2
                        class="mt-16 text-2xl font-black uppercase leading-tight tracking-[-0.04em]"
                    >
                        {{ offer.title }}
                    </h2>
                    <p class="mt-4 text-sm leading-7 text-[#f7edcf]/60">
                        {{ offer.body }}
                    </p>
                </article>
            </div>
        </div>
    </section>

    <!-- 2. Trusted by media houses -->
    <section class="bg-white/60 px-5 py-16 sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <div v-if="first('mediaIntro')" class="max-w-2xl">
                <p
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/50"
                >
                    {{ first('mediaIntro').eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ first('mediaIntro').title }}
                </h2>
                <p class="mt-6 text-base leading-7 text-[#073b2a]/65">
                    {{ first('mediaIntro').body }}
                </p>
            </div>
            <div
                class="mt-12 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4"
            >
                <PartnerTile
                    v-for="partner in partners"
                    :key="partner.id"
                    :partner="partner"
                />
            </div>
        </div>
    </section>

    <!-- 3. Payments partners -->
    <section
        v-if="payment_partners.length"
        class="bg-[#f7edcf] px-5 py-16 sm:px-10 lg:py-20"
    >
        <div
            class="mx-auto grid w-full max-w-7xl gap-10 md:grid-cols-[1fr_1fr] md:items-center"
        >
            <div v-if="first('paymentsIntro')">
                <p
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/50"
                >
                    {{ first('paymentsIntro').eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-5xl"
                >
                    {{ first('paymentsIntro').title }}
                </h2>
                <p class="mt-6 text-base leading-7 text-[#073b2a]/65">
                    {{ first('paymentsIntro').body }}
                </p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <PartnerTile
                    v-for="partner in payment_partners"
                    :key="partner.id"
                    :partner="partner"
                />
            </div>
        </div>
    </section>

    <!-- 4. What you get with REL -->
    <section class="bg-white/60 px-5 py-16 sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <div v-if="first('getIntro')" class="max-w-2xl">
                <p
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/50"
                >
                    {{ first('getIntro').eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ first('getIntro').title }}
                </h2>
                <p class="mt-6 text-base leading-7 text-[#073b2a]/65">
                    {{ first('getIntro').body }}
                </p>
            </div>
            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <article
                    v-for="item in group('get')"
                    :key="item.id"
                    class="rounded-3xl border border-[#073b2a]/10 p-7"
                >
                    <FeatureIcon
                        :name="item.icon"
                        class="size-8 text-[#e4bc19]"
                    />
                    <h3
                        class="mt-10 text-xl font-black uppercase tracking-[-0.03em]"
                    >
                        {{ item.title }}
                    </h3>
                    <p class="mt-3 text-sm leading-7 text-[#073b2a]/65">
                        {{ item.body }}
                    </p>
                </article>
            </div>
        </div>
    </section>

    <!-- 5. What we ask of media partners -->
    <section class="bg-[#073b2a] px-5 py-16 text-[#f7edcf] sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <div v-if="first('askIntro')" class="max-w-2xl">
                <p
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
                >
                    {{ first('askIntro').eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ first('askIntro').title }}
                </h2>
                <p class="mt-6 text-base leading-7 text-[#f7edcf]/65">
                    {{ first('askIntro').body }}
                </p>
            </div>
            <ol class="mt-12 grid gap-5 sm:grid-cols-2">
                <li
                    v-for="(item, index) in group('ask')"
                    :key="item.id"
                    class="flex gap-5 rounded-3xl border border-[#f7edcf]/15 p-6 sm:p-8"
                >
                    <span
                        class="text-3xl font-black text-[#e4bc19]"
                        >0{{ index + 1 }}</span
                    >
                    <div>
                        <h3 class="text-xl font-black uppercase">
                            {{ item.title }}
                        </h3>
                        <p class="mt-3 text-sm leading-7 text-[#f7edcf]/65">
                            {{ item.body }}
                        </p>
                    </div>
                </li>
            </ol>
        </div>
    </section>

    <!-- 6. How partners earn -->
    <section
        v-if="first('earn')"
        class="bg-[#e4bc19] px-5 py-16 text-[#073b2a] sm:px-10 lg:py-24"
    >
        <div class="mx-auto grid w-full max-w-7xl gap-10 md:grid-cols-2">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em]">
                    {{ first('earn').eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ first('earn').title }}
                </h2>
            </div>
            <div class="space-y-6 text-base leading-8 text-[#073b2a]/80">
                <p
                    v-for="(para, i) in (first('earn').body ?? '').split(
                        /\n{2,}/,
                    )"
                    :key="i"
                >
                    {{ para }}
                </p>
            </div>
        </div>
    </section>

    <!-- 7. A dashboard for every role -->
    <section class="bg-white/60 px-5 py-16 sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <div v-if="first('rolesIntro')" class="max-w-2xl">
                <p
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/50"
                >
                    {{ first('rolesIntro').eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ first('rolesIntro').title }}
                </h2>
                <p class="mt-6 text-base leading-7 text-[#073b2a]/65">
                    {{ first('rolesIntro').body }}
                </p>
            </div>
            <div class="mt-12 grid gap-5 lg:grid-cols-3">
                <article
                    v-for="role in group('roles')"
                    :key="role.id"
                    class="rounded-3xl bg-[#f7edcf] p-7 sm:p-9"
                >
                    <FeatureIcon
                        :name="role.icon"
                        class="size-8 text-[#073b2a]"
                    />
                    <h3
                        class="mt-10 text-2xl font-black uppercase tracking-[-0.04em]"
                    >
                        {{ role.title }}
                    </h3>
                    <p class="mt-3 text-sm leading-7 text-[#073b2a]/65">
                        {{ role.body }}
                    </p>
                </article>
            </div>
        </div>
    </section>

    <!-- 8. Getting started -->
    <section class="bg-[#f7edcf] px-5 py-16 sm:px-10 lg:py-24">
        <div class="mx-auto w-full max-w-7xl">
            <div v-if="first('stepsIntro')" class="max-w-2xl">
                <p
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/50"
                >
                    {{ first('stepsIntro').eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ first('stepsIntro').title }}
                </h2>
                <p class="mt-6 text-base leading-7 text-[#073b2a]/65">
                    {{ first('stepsIntro').body }}
                </p>
            </div>
            <ol class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <li
                    v-for="(step, index) in group('steps')"
                    :key="step.id"
                    class="border-t-2 border-[#073b2a] pt-5"
                >
                    <span class="text-4xl font-black text-[#073b2a]/30"
                        >0{{ index + 1 }}</span
                    >
                    <h3 class="mt-4 text-xl font-black uppercase leading-tight">
                        {{ step.title }}
                    </h3>
                    <p class="mt-3 text-sm leading-7 text-[#073b2a]/65">
                        {{ step.body }}
                    </p>
                </li>
            </ol>
        </div>
    </section>

    <!-- 9. Closing CTA -->
    <CtaBand v-if="first('cta')" :block="first('cta')" />
</template>
