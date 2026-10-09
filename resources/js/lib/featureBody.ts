/**
 * Splits a Feature body into paragraphs and "- " tick bullets.
 * Editors write plain text; lines starting with "- " become bullets.
 */
export function splitBody(body: string | null | undefined): {
    paragraphs: string[];
    bullets: string[];
} {
    const paragraphs: string[] = [];
    const bullets: string[] = [];

    for (const raw of (body ?? '').split(/\r?\n/)) {
        const line = raw.trim();

        if (!line) {
            continue;
        }

        if (line.startsWith('- ')) {
            bullets.push(line.slice(2).trim());
        } else {
            paragraphs.push(line);
        }
    }

    return { paragraphs, bullets };
}
