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

/** One link in the public navbar, built server-side from the pages table. */
export type SiteNavItem = {
    slug: string;
    label: string;
    href: string;
};

/**
 * Shared Inertia props describing which public pages are switched on.
 *
 * - nav: active pages that are set to show in the navbar, in order (excludes home and contact).
 * - activePaths: every active public path, e.g. ['/', '/about', '/contact']. The footer and
 *   buttons use it to hide links to pages that have been switched off.
 * - contactEnabled: whether /contact is active (drives the "Connect" button).
 * - previewingInactive: true when a signed-in staff member is viewing an inactive page.
 */
export type SiteVisibilityProps = {
    nav: SiteNavItem[];
    activePaths: string[];
    contactEnabled: boolean;
    previewingInactive: boolean;
};
