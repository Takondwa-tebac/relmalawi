<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PageIntro from '@/components/guest/PageIntro.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import type { PageIntroData } from '@/types';

defineOptions({ layout: GuestLayout });

const props = defineProps<{
    page: PageIntroData;
    recaptchaSiteKey: string | null;
}>();

type Grecaptcha = {
    ready: (callback: () => void) => void;
    execute: (siteKey: string, options: { action: string }) => Promise<string>;
};

const form = useForm({
    name: '',
    email: '',
    message: '',
    // Honeypot: hidden from humans, bots tend to fill it.
    website: '',
    recaptcha_token: '',
});

const captchaError = ref<string | null>(null);

// Spam-check, rate-limit and reCAPTCHA problems share one friendly notice.
const notice = computed(
    () =>
        captchaError.value ||
        form.errors.recaptcha_token ||
        (form.errors as Record<string, string | undefined>).throttle ||
        null,
);

function grecaptcha(): Grecaptcha | undefined {
    return (window as unknown as { grecaptcha?: Grecaptcha }).grecaptcha;
}

let scriptPromise: Promise<void> | null = null;

// Load reCAPTCHA v3 lazily, and only when a site key is configured.
function loadRecaptcha(): Promise<void> {
    if (!props.recaptchaSiteKey || grecaptcha()) {
        return Promise.resolve();
    }

    scriptPromise ??= new Promise<void>((resolve, reject) => {
        const script = document.createElement('script');
        script.src = `https://www.google.com/recaptcha/api.js?render=${encodeURIComponent(props.recaptchaSiteKey!)}`;
        script.async = true;
        script.onload = () => resolve();
        script.onerror = () => {
            scriptPromise = null;
            reject(new Error('reCAPTCHA failed to load'));
        };
        document.head.appendChild(script);
    });

    return scriptPromise;
}

async function getToken(): Promise<string> {
    if (!props.recaptchaSiteKey) {
        return '';
    }

    await loadRecaptcha();

    const api = grecaptcha();

    if (!api) {
        throw new Error('reCAPTCHA unavailable');
    }

    return new Promise<string>((resolve, reject) => {
        api.ready(() => {
            api.execute(props.recaptchaSiteKey!, { action: 'contact' })
                .then(resolve)
                .catch(reject);
        });
    });
}

async function submit() {
    captchaError.value = null;

    try {
        form.recaptcha_token = await getToken();
    } catch {
        captchaError.value =
            'We could not run the spam check. Please check your connection and try again.';

        return;
    }

    form.post('/contact', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onFinish: () => form.reset('recaptcha_token'),
    });
}

onMounted(() => {
    // Warm up the script so the first submit is instant; failures surface on submit.
    loadRecaptcha().catch(() => {});
});

const fieldClass =
    'rounded-xl border border-[#073b2a]/15 bg-transparent px-4 py-3 text-sm normal-case tracking-normal outline-none focus:border-[#073b2a]';
const labelClass =
    'flex flex-col gap-2 text-xs font-bold uppercase tracking-[0.15em]';
</script>

<template>
    <Head :title="page.meta_title ?? page.title" />
    <section
        class="mx-auto grid min-h-[calc(100vh-165px)] w-full max-w-7xl gap-14 px-6 py-16 sm:px-10 lg:grid-cols-[.9fr_1.1fr] lg:px-14"
    >
        <PageIntro :page="page" />
        <div
            class="rounded-[2rem] bg-[#f7edcf] p-7 text-[#073b2a] sm:p-10"
        >
            <div v-if="form.wasSuccessful" class="py-16" role="status">
                <p
                    class="text-xs font-bold uppercase tracking-[0.2em] text-[#073b2a]/50"
                >
                    Transmission received
                </p>
                <h2
                    class="mt-3 text-4xl font-black uppercase tracking-[-0.05em]"
                >
                    We'll be in touch.
                </h2>
            </div>
            <form
                v-else
                class="flex flex-col gap-5"
                @submit.prevent="submit"
            >
                <label :class="labelClass">
                    Name
                    <input
                        v-model="form.name"
                        required
                        name="name"
                        autocomplete="name"
                        maxlength="120"
                        :class="fieldClass"
                    />
                    <InputError :message="form.errors.name" />
                </label>
                <label :class="labelClass">
                    Email
                    <input
                        v-model="form.email"
                        required
                        type="email"
                        name="email"
                        autocomplete="email"
                        :class="fieldClass"
                    />
                    <InputError :message="form.errors.email" />
                </label>
                <label :class="labelClass">
                    Message
                    <textarea
                        v-model="form.message"
                        required
                        name="message"
                        rows="5"
                        maxlength="5000"
                        :class="[fieldClass, 'resize-none']"
                    />
                    <InputError :message="form.errors.message" />
                </label>
                <div
                    aria-hidden="true"
                    class="absolute -left-[9999px] h-0 w-0 overflow-hidden"
                >
                    <label>
                        Website
                        <input
                            v-model="form.website"
                            name="website"
                            tabindex="-1"
                            autocomplete="off"
                        />
                    </label>
                </div>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-xl bg-[#073b2a] px-5 py-3 text-sm font-bold uppercase tracking-[0.1em] text-[#f7edcf] transition-colors hover:bg-[#0d6347] disabled:opacity-60"
                >
                    {{ form.processing ? 'Sending...' : 'Send transmission' }}
                </button>
                <p
                    v-if="notice"
                    class="rounded-xl bg-[#073b2a]/10 px-4 py-3 text-sm font-semibold text-[#073b2a]"
                    role="alert"
                >
                    {{ notice }}
                </p>
                <p
                    v-if="recaptchaSiteKey"
                    class="text-xs normal-case leading-relaxed text-[#073b2a]/60"
                >
                    This site is protected by reCAPTCHA and the Google
                    <a
                        href="https://policies.google.com/privacy"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="underline"
                        >Privacy Policy</a
                    >
                    and
                    <a
                        href="https://policies.google.com/terms"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="underline"
                        >Terms of Service</a
                    >
                    apply.
                </p>
            </form>
        </div>
    </section>
</template>
