<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import CtaBand from '@/components/guest/CtaBand.vue';
import FaqAccordion from '@/components/guest/FaqAccordion.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';
import type { FaqData } from '@/types/faq';
import type { FeatureItem } from '@/types/features';

defineOptions({ layout: GuestLayout });

const props = defineProps<{ page: PageIntroData; faqs: FaqData[] }>();

const ALL = 'All';

const categories = computed(() => [
    ...new Set(props.faqs.map((faq) => faq.category)),
]);

const active = ref<string>(ALL);

const groups = computed(() =>
    categories.value
        .filter((category) => active.value === ALL || active.value === category)
        .map((category) => ({
            category,
            faqs: props.faqs.filter((faq) => faq.category === category),
        })),
);

// Static UI band: the link and button text are interface labels.
const cta: FeatureItem = {
    id: 0,
    group: 'faq.cta',
    eyebrow: 'Still have questions?',
    title: 'Talk to the team.',
    body: null,
    icon: null,
    meta: { button_label: 'Contact us', button_url: '/contact' },
};
</script>

<template>
    <Head :title="page.meta_title ?? page.title">
        <meta
            v-if="page.meta_description"
            head-key="description"
            name="description"
            :content="page.meta_description"
        />
    </Head>

    <section class="bg-[#f7edcf]">
        <div
            class="mx-auto w-full max-w-7xl px-5 py-14 sm:px-10 lg:px-14 lg:py-24"
        >
            <PageIntro :page="page" />

            <div
                v-if="categories.length > 1"
                class="mt-12 flex flex-wrap gap-3"
                role="group"
                aria-label="Filter questions by category"
            >
                <button
                    v-for="category in [ALL, ...categories]"
                    :key="category"
                    type="button"
                    :aria-pressed="active === category"
                    :class="[
                        'rounded-full border px-4 py-2 text-xs font-bold tracking-[0.14em] uppercase transition-colors',
                        active === category
                            ? 'border-[#073b2a] bg-[#073b2a] text-[#f7edcf]'
                            : 'border-[#073b2a]/20 text-[#073b2a]/70 hover:border-[#073b2a]',
                    ]"
                    @click="active = category"
                >
                    {{ category }}
                </button>
            </div>

            <div class="mt-12 space-y-16">
                <section
                    v-for="group in groups"
                    :key="group.category"
                    class="grid gap-8 lg:grid-cols-[.6fr_1.4fr]"
                >
                    <h2
                        class="text-3xl leading-none font-black tracking-[-0.05em] uppercase sm:text-4xl"
                    >
                        {{ group.category }}
                    </h2>
                    <FaqAccordion :faqs="group.faqs" :open-first="false" />
                </section>
                <p v-if="!faqs.length" class="text-base text-[#073b2a]/65">
                    Questions will appear here soon.
                </p>
            </div>
        </div>
    </section>

    <CtaBand :block="cta" />
</template>
