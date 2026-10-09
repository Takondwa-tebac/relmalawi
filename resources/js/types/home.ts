export type CampaignData = {
    id: number;
    title: string;
    alt: string;
    description: string | null;
    link_url: string | null;
    image: string | null;
    thumb: string | null;
};

export type StatData = {
    id: number;
    value: string;
    label: string;
};

export type HomeHero = {
    title_tail: string;
    secondary: string | null;
};

export type HomePartnerData = {
    id: number;
    name: string;
    type: string;
    website_url: string | null;
    logo_url: string | null;
};
