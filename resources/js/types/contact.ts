/** A numbered step on the How it works page (number is derived server-side). */
export type HowItWorksStepData = {
    id: number;
    number: string;
    title: string;
    body: string | null;
    detail: string | null;
    icon: string | null;
};
