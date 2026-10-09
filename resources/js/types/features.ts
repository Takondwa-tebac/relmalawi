/** Allowed icon keys: keep in sync with App\Enums\FeatureIcon. */
export type FeatureIconKey =
    | 'radio'
    | 'gamepad'
    | 'shield-check'
    | 'shuffle'
    | 'bar-chart'
    | 'lock'
    | 'eye'
    | 'crown'
    | 'gift'
    | 'file-check'
    | 'scale'
    | 'check-circle'
    | 'smartphone'
    | 'wallet'
    | 'mic'
    | 'users'
    | 'building'
    | 'handshake'
    | 'headset'
    | 'ticket'
    | 'cog';

/** One CMS-managed card/block (features table). */
export type FeatureItem = {
    id: number;
    group: string;
    eyebrow: string | null;
    title: string;
    body: string | null;
    icon: FeatureIconKey | null;
    meta: { button_label?: string; button_url?: string } & Record<string, unknown>;
};

/** Published features of a page, keyed by group (e.g. 'about.pillars'). */
export type FeaturesByGroup = Record<string, FeatureItem[]>;
