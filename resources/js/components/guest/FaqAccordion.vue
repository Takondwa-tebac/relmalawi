<script setup lang="ts">
import { ChevronDown } from '@lucide/vue';
import { ref } from 'vue';
import type { FaqData } from '@/types/faq';

const props = withDefaults(
    defineProps<{
        faqs: FaqData[];
        /** Open the first question by default. */
        openFirst?: boolean;
        /** Tone of the surrounding section so the lines read on both backgrounds. */
        tone?: 'light' | 'dark';
    }>(),
    { openFirst: true, tone: 'light' },
);

const openId = ref<number | null>(props.openFirst ? (props.faqs[0]?.id ?? null) : null);

const toggle = (id: number) => {
    openId.value = openId.value === id ? null : id;
};
</script>

<template>
    <div
        :class="[
            'divide-y border-y',
            tone === 'light'
                ? 'divide-[#073b2a]/15 border-[#073b2a]/15'
                : 'divide-[#f7edcf]/15 border-[#f7edcf]/15',
        ]"
    >
        <div v-for="(faq, index) in faqs" :key="faq.id">
            <h3>
                <button
                    type="button"
                    class="flex w-full items-start gap-5 py-6 text-left"
                    :aria-expanded="openId === faq.id"
                    :aria-controls="`faq-panel-${faq.id}`"
                    @click="toggle(faq.id)"
                >
                    <span
                        class="w-8 shrink-0 pt-1 text-sm font-black text-[#e4bc19]"
                    >
                        {{ String(index + 1).padStart(2, '0') }}
                    </span>
                    <span
                        class="flex-1 text-lg font-bold leading-snug sm:text-xl"
                    >
                        {{ faq.question }}
                    </span>
                    <ChevronDown
                        :class="[
                            'mt-1 size-5 shrink-0 transition-transform duration-300',
                            openId === faq.id ? 'rotate-180' : '',
                        ]"
                    />
                </button>
            </h3>
            <div
                v-show="openId === faq.id"
                :id="`faq-panel-${faq.id}`"
                role="region"
                class="pb-7 pl-13 pr-10"
            >
                <p
                    :class="[
                        'whitespace-pre-line text-base leading-7',
                        tone === 'light'
                            ? 'text-[#073b2a]/70'
                            : 'text-[#f7edcf]/70',
                    ]"
                >
                    {{ faq.answer }}
                </p>
            </div>
        </div>
    </div>
</template>
