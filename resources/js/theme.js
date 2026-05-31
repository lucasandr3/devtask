/**
 * GestorPro Theme Manager
 * Handles color themes and dark mode switching
 */

const ThemeManager = {
    themes: [
        { id: 'blue', name: 'Azul Oceano', color: '#3b82f6' },
        { id: 'indigo', name: 'Índigo Noturno', color: '#6366f1' },
        { id: 'emerald', name: 'Esmeralda', color: '#10b981' },
        { id: 'rose', name: 'Rosa Elegante', color: '#f43f5e' },
        { id: 'amber', name: 'Âmbar Quente', color: '#f59e0b' },
        { id: 'violet', name: 'Violeta', color: '#8b5cf6' },
        { id: 'teal', name: 'Verde-azulado', color: '#14b8a6' },
        { id: 'slate', name: 'Ardósia', color: '#64748b' },
        { id: 'cyan', name: 'Ciano', color: '#06b6d4' },
        { id: 'fuchsia', name: 'Fúcsia', color: '#d946ef' },
    ],

    init() {
        document.documentElement.classList.add('no-transitions');

        this.loadTheme();
        this.loadDarkMode();

        setTimeout(() => {
            document.documentElement.classList.remove('no-transitions');
        }, 100);

        this.watchSystemDarkMode();
    },

    getThemes() {
        return this.themes;
    },

    isAuthenticated() {
        return window.__isAuthenticated === true;
    },

    getServerTheme() {
        const meta = document.querySelector('meta[name="user-theme"]');
        return meta?.content || window.__userTheme || 'blue';
    },

    getCurrentTheme() {
        if (this.isAuthenticated()) {
            return this.getServerTheme();
        }

        return localStorage.getItem('gestorpro-theme') || 'blue';
    },

    applyTheme(themeId) {
        const validTheme = this.themes.find(t => t.id === themeId);
        if (!validTheme) {
            console.warn(`Invalid theme: ${themeId}`);
            return false;
        }

        if (themeId === 'blue') {
            document.documentElement.removeAttribute('data-theme');
        } else {
            document.documentElement.setAttribute('data-theme', themeId);
        }

        return true;
    },

    setTheme(themeId, { persist = true } = {}) {
        if (!this.applyTheme(themeId)) {
            return;
        }

        localStorage.setItem('gestorpro-theme', themeId);

        if (this.isAuthenticated()) {
            window.__userTheme = themeId;

            const meta = document.querySelector('meta[name="user-theme"]');
            if (meta) {
                meta.content = themeId;
            }
        }

        if (persist && this.isAuthenticated()) {
            this.persistTheme(themeId);
        }

        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: themeId } }));
    },

    async persistTheme(themeId) {
        if (!this.isAuthenticated()) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

        try {
            const response = await fetch('/configuracoes/tema', {
                method: 'PATCH',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ theme_color: themeId }),
            });

            if (!response.ok) {
                console.warn('Failed to save theme preference', response.status);
            }
        } catch (error) {
            console.warn('Failed to save theme preference', error);
        }
    },

    loadTheme() {
        const savedTheme = this.getCurrentTheme();
        this.applyTheme(savedTheme);
        localStorage.setItem('gestorpro-theme', savedTheme);
    },

    getMode() {
        const saved = localStorage.getItem('gestorpro-mode');
        if (saved) {
            return saved;
        }
        const darkMode = localStorage.getItem('gestorpro-dark-mode');
        if (darkMode === 'true') return 'dark';
        if (darkMode === 'false') return 'light';
        return 'system';
    },

    setMode(mode) {
        localStorage.setItem('gestorpro-mode', mode);

        if (mode === 'light') {
            this.disableDarkMode();
        } else if (mode === 'dark') {
            this.enableDarkMode();
        } else {
            localStorage.removeItem('gestorpro-dark-mode');
            this.loadDarkMode();
        }

        window.dispatchEvent(new CustomEvent('theme-mode-changed', { detail: { mode } }));
    },

    isDarkMode() {
        const saved = localStorage.getItem('gestorpro-dark-mode');
        if (saved !== null) {
            return saved === 'true';
        }
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    },

    enableDarkMode() {
        document.documentElement.classList.add('dark');
        localStorage.setItem('gestorpro-dark-mode', 'true');
        window.dispatchEvent(new CustomEvent('dark-mode-changed', { detail: { darkMode: true } }));
    },

    disableDarkMode() {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('gestorpro-dark-mode', 'false');
        window.dispatchEvent(new CustomEvent('dark-mode-changed', { detail: { darkMode: false } }));
    },

    toggleDarkMode() {
        if (this.isDarkMode()) {
            this.disableDarkMode();
        } else {
            this.enableDarkMode();
        }
    },

    loadDarkMode() {
        if (this.isDarkMode()) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },

    watchSystemDarkMode() {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', (e) => {
            if (localStorage.getItem('gestorpro-dark-mode') === null) {
                if (e.matches) {
                    this.enableDarkMode();
                } else {
                    this.disableDarkMode();
                }
            }
        });
    },

    resetToSystemPreference() {
        localStorage.removeItem('gestorpro-dark-mode');
        this.loadDarkMode();
    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => ThemeManager.init());
} else {
    ThemeManager.init();
}

window.ThemeManager = ThemeManager;

export default ThemeManager;
