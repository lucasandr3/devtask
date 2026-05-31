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
        // Disable transitions on page load to prevent flash
        document.documentElement.classList.add('no-transitions');
        
        this.loadTheme();
        this.loadDarkMode();
        
        // Re-enable transitions after a short delay
        setTimeout(() => {
            document.documentElement.classList.remove('no-transitions');
        }, 100);

        // Listen for system dark mode changes
        this.watchSystemDarkMode();
    },

    /**
     * Get all available themes
     */
    getThemes() {
        return this.themes;
    },

    /**
     * Get current theme ID
     */
    getCurrentTheme() {
        return localStorage.getItem('gestorpro-theme') || 'blue';
    },

    /**
     * Set color theme
     */
    setTheme(themeId) {
        const validTheme = this.themes.find(t => t.id === themeId);
        if (!validTheme) {
            console.warn(`Invalid theme: ${themeId}`);
            return;
        }

        // Remove all theme attributes
        if (themeId === 'blue') {
            document.documentElement.removeAttribute('data-theme');
        } else {
            document.documentElement.setAttribute('data-theme', themeId);
        }
        
        localStorage.setItem('gestorpro-theme', themeId);
        
        // Dispatch custom event for reactivity
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: themeId } }));
    },

    /**
     * Load saved theme from localStorage
     */
    loadTheme() {
        const savedTheme = this.getCurrentTheme();
        this.setTheme(savedTheme);
    },

    /**
     * Get theme mode: light, dark, or system
     */
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

    /**
     * Set theme mode
     */
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

    /**
     * Check if dark mode is enabled
     */
    isDarkMode() {
        const saved = localStorage.getItem('gestorpro-dark-mode');
        if (saved !== null) {
            return saved === 'true';
        }
        // Default to system preference
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    },

    /**
     * Enable dark mode
     */
    enableDarkMode() {
        document.documentElement.classList.add('dark');
        localStorage.setItem('gestorpro-dark-mode', 'true');
        window.dispatchEvent(new CustomEvent('dark-mode-changed', { detail: { darkMode: true } }));
    },

    /**
     * Disable dark mode
     */
    disableDarkMode() {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('gestorpro-dark-mode', 'false');
        window.dispatchEvent(new CustomEvent('dark-mode-changed', { detail: { darkMode: false } }));
    },

    /**
     * Toggle dark mode
     */
    toggleDarkMode() {
        if (this.isDarkMode()) {
            this.disableDarkMode();
        } else {
            this.enableDarkMode();
        }
    },

    /**
     * Load saved dark mode preference
     */
    loadDarkMode() {
        if (this.isDarkMode()) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    },

    /**
     * Watch for system dark mode changes
     */
    watchSystemDarkMode() {
        const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        mediaQuery.addEventListener('change', (e) => {
            // Only apply if user hasn't set a preference
            if (localStorage.getItem('gestorpro-dark-mode') === null) {
                if (e.matches) {
                    this.enableDarkMode();
                } else {
                    this.disableDarkMode();
                }
            }
        });
    },

    /**
     * Reset to system preference
     */
    resetToSystemPreference() {
        localStorage.removeItem('gestorpro-dark-mode');
        this.loadDarkMode();
    }
};

// Auto-initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => ThemeManager.init());
} else {
    ThemeManager.init();
}

// Make it globally available
window.ThemeManager = ThemeManager;

export default ThemeManager;
