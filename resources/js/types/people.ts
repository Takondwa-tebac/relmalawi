export type TeamMemberData = {
    id: number;
    name: string;
    role: string;
    summary: string | null;
    bio: string | null;
    photo_url: string | null;
};

export type PartnerData = {
    id: number;
    name: string;
    type: 'Radio' | 'Television' | 'Radio & Television' | 'Mobile money';
    website_url: string | null;
    logo_url: string | null;
};
