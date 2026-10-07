export type TeamMemberData = {
    id: number;
    name: string;
    role: string;
    bio: string | null;
    photo_url: string | null;
};

export type PartnerData = {
    id: number;
    name: string;
    type: 'Radio' | 'Television' | 'Radio & Television';
    website_url: string | null;
    logo_url: string | null;
};
