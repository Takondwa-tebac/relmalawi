export type SiteSettings = {
    banner_text: string;
    contact_email: string;
    footer_name: string;
    est_year: string;
    codes_count: number;
    codes_pattern: string;
};

/** Editable intro copy for a public page (pages table). */
export type PageIntroData = {
    slug: string;
    eyebrow: string | null;
    title: string;
    title_accent: string | null;
    description: string | null;
    meta_title: string | null;
    meta_description: string | null;
};
