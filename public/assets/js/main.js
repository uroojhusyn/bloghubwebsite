/**
 * =============================================================================
 * BlogHub — main.js
 * -----------------------------------------------------------------------------
 * Site-wide behaviour. Modules are independent and self-guard, so a missing
 * element on a given page never breaks the rest of the shell.
 *
 * No frameworks: vanilla ES6+ only.
 * =============================================================================
 */

'use strict';

const THEME_KEY = 'bloghub-theme';

/* =============================================================================
 * MODULE: THEME TOGGLE
 * The stored preference is applied pre-paint by the inline snippet in the
 * layout <head>; this module only handles the toggle and persistence.
 * ========================================================================== */
const ThemeController = (() => {
    const getTheme = () =>
        document.documentElement.getAttribute('data-theme') || 'light';

    const setTheme = (theme) => {
        document.documentElement.setAttribute('data-theme', theme);
        try {
            localStorage.setItem(THEME_KEY, theme);
        } catch {
            /* Storage can be blocked (private mode); the in-page toggle still works. */
        }
        syncToggle(theme);
    };

    const syncToggle = (theme) => {
        const toggle = document.getElementById('theme-toggle');
        if (!toggle) {
            return;
        }
        const isDark = theme === 'dark';
        toggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        toggle.setAttribute('aria-pressed', String(isDark));

        const icon = toggle.querySelector('i');
        if (icon) {
            icon.className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        }
    };

    const init = () => {
        syncToggle(getTheme());

        const toggle = document.getElementById('theme-toggle');
        if (!toggle) {
            return;
        }

        toggle.addEventListener('click', () => {
            setTheme(getTheme() === 'dark' ? 'light' : 'dark');
        });
    };

    return { init, getTheme, setTheme };
})();

/* =============================================================================
 * MODULE: SCROLL PROGRESS BAR
 * Fills the 3px bar at the top of the viewport as the document is scrolled.
 * ========================================================================== */
const ScrollProgress = (() => {
    const init = () => {
        const bar = document.getElementById('scroll-progress');
        if (!bar) {
            return;
        }

        const update = () => {
            const scrollable = document.documentElement.scrollHeight - window.innerHeight;
            const progress = scrollable > 0 ? (window.scrollY / scrollable) * 100 : 0;
            bar.style.width = `${Math.min(Math.max(progress, 0), 100)}%`;
        };

        window.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        update();
    };

    return { init };
})();

/* =============================================================================
 * MODULE: NAVBAR SHADOW ON SCROLL
 * ========================================================================== */
const NavbarShadow = (() => {
    const init = () => {
        const navbar = document.getElementById('site-navbar');
        if (!navbar) {
            return;
        }

        const update = () => {
            navbar.classList.toggle('is-scrolled', window.scrollY > 8);
        };

        window.addEventListener('scroll', update, { passive: true });
        update();
    };

    return { init };
})();

/* =============================================================================
 * MODULE: MOBILE MENU (off-canvas)
 * Keeps aria-expanded in sync, closes on Escape, and traps the backdrop.
 * ========================================================================== */
const MobileMenu = (() => {
    let menu;
    let backdrop;
    let toggle;

    const isOpen = () => Boolean(menu && menu.classList.contains('is-open'));

    const open = () => {
        menu.classList.add('is-open');
        backdrop.classList.add('is-visible');
        toggle.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
        menu.querySelector('a, button')?.focus();
    };

    const close = () => {
        if (!isOpen()) {
            return;
        }
        menu.classList.remove('is-open');
        backdrop.classList.remove('is-visible');
        toggle.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
        toggle.focus();
    };

    const init = () => {
        menu = document.getElementById('mobile-menu');
        backdrop = document.getElementById('menu-backdrop');
        toggle = document.getElementById('navbar-toggle');
        if (!menu || !backdrop || !toggle) {
            return;
        }

        toggle.addEventListener('click', () => (isOpen() ? close() : open()));
        backdrop.addEventListener('click', close);

        // Explicit close control in the off-canvas header.
        menu.querySelectorAll('[data-menu-close]').forEach((control) => {
            control.addEventListener('click', close);
        });

        menu.addEventListener('click', (event) => {
            if (event.target.closest('a')) {
                close();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && isOpen()) {
                close();
            }
        });
    };

    return { init, close };
})();

/* =============================================================================
 * MODULE: BACK TO TOP
 * ========================================================================== */
const BackToTop = (() => {
    const init = () => {
        const button = document.getElementById('back-to-top');
        if (!button) {
            return;
        }

        const update = () => {
            button.classList.toggle('is-visible', window.scrollY > 480);
        };

        window.addEventListener('scroll', update, { passive: true });
        update();

        button.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
            button.blur();
        });
    };

    return { init };
})();

/* =============================================================================
 * MODULE: TOAST NOTIFICATIONS
 * ========================================================================== */
const Toast = (() => {
    /**
     * Push a message onto the shared toast region.
     * @param {string} message
     * @param {{type?:'info'|'success'|'danger', timeout?:number}} options
     */
    const show = (message, { type = 'info', timeout = 5000 } = {}) => {
        const region = document.getElementById('toast-region');
        if (!region) {
            return;
        }

        const item = document.createElement('li');
        item.className = `toast-item toast-item--${type}`;
        item.setAttribute('role', 'status');
        item.innerHTML = `
            <div class="toast-item__body">${BlogHub.escapeHtml(message)}</div>
            <button type="button" class="icon-btn toast-item__close" aria-label="Dismiss notification">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>`;

        item.querySelector('button').addEventListener('click', () => item.remove());
        region.appendChild(item);

        window.setTimeout(() => item.remove(), timeout);
    };

    const init = () => {
        document.addEventListener('submit', (event) => {
            const form = event.target.closest('form[data-toast]');
            if (!form) {
                return;
            }
            // Prototype shell: no backend persistence yet.
            event.preventDefault();
            show(form.dataset.toast, { type: 'success' });
            form.reset();
        });
    };

    return { init, show };
})();

/* =============================================================================
 * BOOT
 * ========================================================================== */
document.addEventListener('DOMContentLoaded', () => {
    ThemeController.init();
    ScrollProgress.init();
    NavbarShadow.init();
    MobileMenu.init();
    BackToTop.init();
    Toast.init();
});

// Exposed so page-level scripts can surface messages.
window.BlogHubToast = Toast;
