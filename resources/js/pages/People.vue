<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { ArrowUpRight, Check, Users } from '@lucide/vue';
import Lines from '@/components/guest/Lines.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData, SiteSettings } from '@/types';
import type { FeaturesByGroup } from '@/types/features';
import type { TeamMemberData } from '@/types/people';

defineOptions({ layout: GuestLayout });

const props = defineProps<{
    page: PageIntroData;
    features: FeaturesByGroup;
    team: TeamMemberData[];
}>();

const site = usePage<{ site: SiteSettings }>().props.site;

/** The bio column holds one credential per line. */
const credentials = (bio: string | null): string[] =>
    (bio ?? '')
        .split('\n')
        .map((line) => line.trim())
        .filter(Boolean);

const group = (name: string) => props.features[name] ?? [];
const first = (name: string) => group(name)[0];
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />
            <div class="mt-16 grid gap-6 lg:grid-cols-3">
                <article
                    v-for="member in team"
                    :key="member.id"
                    class="flex flex-col overflow-hidden rounded-3xl bg-white/60 shadow-sm"
                >
                    <div class="aspect-[4/5] overflow-hidden bg-[#073b2a]">
                        <img
                            v-if="member.photo_url"
                            :src="member.photo_url"
                            :alt="`${member.name}, ${member.role}`"
                            class="size-full object-cover object-top"
                        />
                    </div>
                    <div class="flex flex-1 flex-col p-7 sm:p-9">
                        <p
                            class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#073b2a]/45"
                        >
                            {{ member.role }}
                        </p>
                        <h2
                            class="mt-2 text-3xl font-black uppercase leading-none tracking-[-0.05em]"
                        >
                            {{ member.name }}
                        </h2>
                        <p
                            v-if="member.summary"
                            class="mt-4 text-base font-semibold leading-7 text-[#073b2a]"
                        >
                            {{ member.summary }}
                        </p>
                        <ul
                            v-if="credentials(member.bio).length"
                            class="mt-5 space-y-3 border-t border-[#073b2a]/10 pt-5"
                        >
                            <li
                                v-for="line in credentials(member.bio)"
                                :key="line"
                                class="flex gap-3 text-sm leading-6 text-[#073b2a]/70"
                            >
                                <Check
                                    class="mt-1 size-4 shrink-0 text-[#e4bc19]"
                                />
                                <span>{{ line }}</span>
                            </li>
                        </ul>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section
        v-if="group('brings').length"
        class="bg-[#e4bc19] px-5 py-16 text-[#073b2a] sm:px-10 lg:py-24"
    >
        <div class="mx-auto w-full max-w-7xl">
            <template v-if="first('bringsIntro')">
                <p class="text-xs font-bold uppercase tracking-[0.2em]">
                    {{ first('bringsIntro').eyebrow }}
                </p>
                <h2
                    class="mt-3 max-w-3xl text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    {{ first('bringsIntro').title }}
                </h2>
            </template>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="item in group('brings')"
                    :key="item.id"
                    class="border-t-2 border-[#073b2a] pt-4"
                >
                    <strong
                        class="block text-4xl font-black uppercase tracking-[-0.06em] sm:text-5xl"
                        >{{ item.title }}</strong
                    >
                    <p class="mt-3 text-sm leading-6 text-[#073b2a]/75">
                        {{ item.body }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-[#073b2a] px-5 py-16 text-[#f7edcf] sm:px-10 lg:py-24">
        <div
            class="mx-auto grid w-full max-w-7xl gap-10 md:grid-cols-[.8fr_1.2fr] md:items-end"
        >
            <div v-if="first('principlesIntro')">
                <p
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#e4bc19]"
                >
                    {{ first('principlesIntro').eyebrow }}
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase leading-none tracking-[-0.06em] sm:text-6xl"
                >
                    <Lines :text="first('principlesIntro').title" />
                </h2>
            </div>
            <div class="grid gap-5 sm:grid-cols-3">
                <div
                    v-for="item in group('principles')"
                    :key="item.id"
                    class="border-t border-[#f7edcf]/20 pt-4"
                >
                    <strong class="text-3xl text-[#e4bc19]">{{
                        item.title
                    }}</strong>
                    <p class="mt-3 text-sm leading-6 text-[#f7edcf]/65">
                        {{ item.body }}
                    </p>
                </div>
            </div>
        </div>
        <div
            v-if="first('join')"
            class="mx-auto mt-14 flex w-full max-w-7xl flex-col gap-5 border-t border-[#f7edcf]/15 pt-8 sm:flex-row sm:items-center sm:justify-between"
        >
            <div>
                <span class="flex items-center gap-3 text-lg font-bold">
                    <Users class="shrink-0 text-[#e4bc19]" />
                    {{ first('join').eyebrow }}
                </span>
                <p class="mt-2 max-w-xl text-sm leading-6 text-[#f7edcf]/65">
                    {{ first('join').title }} {{ first('join').body }}
                </p>
            </div>
            <a
                :href="`mailto:${site.contact_email}`"
                class="group inline-flex shrink-0 items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-[#e4bc19]"
            >
                {{ first('join').meta.button_label }}
                <ArrowUpRight
                    class="size-4 transition-transform group-hover:-translate-y-1 group-hover:translate-x-1"
                />
            </a>
        </div>
    </section>
</template>
