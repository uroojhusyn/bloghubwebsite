/**
 * =============================================================================
 * BlogHub — data.js
 * -----------------------------------------------------------------------------
 * The single source of truth for page content in this phase. No database calls
 * are made anywhere in the frontend; components read from the window-scoped
 * BLOGHUB object instead.
 *
 * Keep every key kebab-case-friendly and stable — components.js and the Blade
 * shells both rely on these shapes.
 * =============================================================================
 */

window.BLOGHUB = {
    /** Blog post summaries: { id, slug, title, excerpt, coverImage, readingMinutes, publishedAt, category, author, tags } */
    posts: [],

    /** Category summaries: { id, slug, name, description, postCount, coverImage } */
    categories: [],

    /** Author summaries: { id, slug, name, avatar, bio, role, postCount, socialLinks } */
    authors: [],

    /** Comments: { id, postId, authorName, authorAvatar, body, createdAt } */
    comments: [],

    /** Testimonials: { id, quote, authorName, authorRole, authorAvatar } */
    testimonials: [],

    /** Site-wide copy reused by the navbar, footer, and auth screens. */
    site: {
        name: 'BlogHub',
        tagline: 'Stories, ideas and insights from writers around the world.',
        supportEmail: 'hello@bloghub.example',
        socialLinks: [
            { label: 'BlogHub on Twitter', platform: 'twitter', url: '#' },
            { label: 'BlogHub on LinkedIn', platform: 'linkedin', url: '#' },
            { label: 'BlogHub on GitHub', platform: 'github', url: '#' },
        ],
    },
};
