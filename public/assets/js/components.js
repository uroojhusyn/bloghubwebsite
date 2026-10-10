/**
 * =============================================================================
 * BlogHub — components.js
 * -----------------------------------------------------------------------------
 * Reusable render helpers shared by every page. Markup built here must mirror
 * the Blade components (kebab-case classes, one H1 stays in the Blade view,
 * alt text and labels on everything).
 *
 * Dependencies: data.js (window.BLOGHUB). Load order is enforced in the layout.
 * =============================================================================
 */

const BlogHub = (() => {
    'use strict';

    /**
     * Escape untrusted strings before injecting them into innerHTML.
     * @param {unknown} value
     * @returns {string}
     */
    const escapeHtml = (value) =>
        String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    /**
     * Format an ISO date string for display.
     * @param {string} isoDate
     * @returns {string} e.g. "Oct 8, 2026"
     */
    const formatDate = (isoDate) => {
        const date = new Date(isoDate);
        if (Number.isNaN(date.getTime())) {
            return '';
        }
        return new Intl.DateTimeFormat('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
        }).format(date);
    };

    /**
     * Build a semantic <article> card for a post.
     * @param {{title:string, slug:string, excerpt:string, coverImage:string,
     *          readingMinutes:number, publishedAt:string,
     *          category?:{name:string}, author?:{name:string, avatar:string}}} post
     * @returns {string} HTML markup
     */
    const postCard = (post) => `
        <article class="card">
            <img class="card__media" src="${escapeHtml(post.coverImage)}"
                 alt="${escapeHtml(post.title)}" loading="lazy" width="640" height="360">
            <div class="card__body">
                ${post.category ? `<a class="badge" href="#/categories/${escapeHtml(post.category.slug ?? '')}">${escapeHtml(post.category.name)}</a>` : ''}
                <h3 class="card__title">
                    <a href="/blog-details?slug=${encodeURIComponent(post.slug ?? '')}">${escapeHtml(post.title)}</a>
                </h3>
                <p class="text-muted">${escapeHtml(post.excerpt)}</p>
                <p class="card__meta">
                    ${post.author ? `${escapeHtml(post.author.name)} · ` : ''}
                    <time datetime="${escapeHtml(post.publishedAt)}">${formatDate(post.publishedAt)}</time>
                    · ${escapeHtml(post.readingMinutes)} min read
                </p>
            </div>
        </article>`;

    /**
     * Build a semantic <li> for an author entry.
     * @param {{name:string, slug:string, avatar:string, bio:string, role:string}} author
     * @returns {string} HTML markup
     */
    const authorItem = (author) => `
        <li class="card">
            <img class="card__media" src="${escapeHtml(author.avatar)}"
                 alt="Portrait of ${escapeHtml(author.name)}" loading="lazy" width="640" height="360">
            <div class="card__body">
                <h3 class="card__title">
                    <a href="/author-profile?slug=${encodeURIComponent(author.slug ?? '')}">${escapeHtml(author.name)}</a>
                </h3>
                <p class="text-muted">${escapeHtml(author.role)}</p>
                <p class="card__meta">${escapeHtml(author.bio)}</p>
            </div>
        </li>`;

    /**
     * Render an empty state block when a collection has no entries.
     * @param {{iconClass:string, title:string, message:string, actionLabel?:string, actionHref?:string}} options
     * @returns {string} HTML markup
     */
    const emptyState = ({ iconClass, title, message, actionLabel, actionHref }) => `
        <div class="empty-state">
            <i class="${escapeHtml(iconClass)} empty-state__icon" aria-hidden="true"></i>
            <h2 class="empty-state__title">${escapeHtml(title)}</h2>
            <p class="empty-state__text">${escapeHtml(message)}</p>
            ${actionLabel ? `<a class="btn btn--primary" href="${escapeHtml(actionHref ?? '/')}">${escapeHtml(actionLabel)}</a>` : ''}
        </div>`;

    /**
     * Mount a collection into a container, falling back to an empty state.
     * @param {string} selector - CSS selector for the mount point
     * @param {Array} items
     * @param {(item:object) => string} render - item renderer
     * @param {{tag?:string, empty:object}} options
     */
    const mount = (selector, items, render, options = {}) => {
        const root = document.querySelector(selector);
        if (!root) {
            return;
        }

        if (!Array.isArray(items) || items.length === 0) {
            root.innerHTML = emptyState(options.empty ?? {});
            return;
        }

        const tag = options.tag ?? 'div';
        root.innerHTML = `<${tag} class="grid-auto">${items.map(render).join('')}</${tag}>`;
    };

    return { escapeHtml, formatDate, postCard, authorItem, emptyState, mount };
})();

// Exposed for page-level scripts pushed via @stack('scripts').
window.BlogHub = BlogHub;
