/**
 * DevTask Theme Manager
 * Handles color themes and dark mode switching
 */

const ThemeManager = {
    themes: [
        { id: 'blue', name: 'Ocean Blue', color: '#3b82f6' },
        { id: 'indigo', name: 'Indigo Night', color: '#6366f1' },
        { id: 'emerald', name: 'Emerald Fresh', color: '#10b981' },
        { id: 'rose', name: 'Rose Elegant', color: '#f43f5e' },
        { id: 'amber', name: 'Amber Warm', color: '#f59e0b' },
        { id: 'violet', name: 'Violet Dream', color: '#8b5cf6' },
        { id: 'teal', name: 'Teal Ocean', color: '#14b8a6' },
        { id: 'slate', name: 'Slate Pro', color: '#64748b' },
        { id: 'cyan', name: 'Cyan Tech', color: '#06b6d4' },
        { id: 'fuchsia', name: 'Fuchsia Bold', color: '#d946ef' },
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
        return localStorage.getItem('devtask-theme') || 'blue';
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
        
        localStorage.setItem('devtask-theme', themeId);
        
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
     * Check if dark mode is enabled
     */
    isDarkMode() {
        const saved = localStorage.getItem('devtask-dark-mode');
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
        localStorage.setItem('devtask-dark-mode', 'true');
        window.dispatchEvent(new CustomEvent('dark-mode-changed', { detail: { darkMode: true } }));
    },

    /**
     * Disable dark mode
     */
    disableDarkMode() {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('devtask-dark-mode', 'false');
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
            if (localStorage.getItem('devtask-dark-mode') === null) {
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
        localStorage.removeItem('devtask-dark-mode');
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
