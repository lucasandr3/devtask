const STORAGE_KEY = 'gestorpro-login-email';

function initLoginEmailPersistence() {
    const form = document.querySelector('[data-login-form]');
    const emailInput = form?.querySelector('[name="email"]');

    if (!form || !emailInput) {
        return;
    }

    const savedEmail = localStorage.getItem(STORAGE_KEY);

    if (savedEmail && !emailInput.value) {
        emailInput.value = savedEmail;
    }

    form.addEventListener('submit', () => {
        const email = emailInput.value.trim();

        if (email) {
            localStorage.setItem(STORAGE_KEY, email);
        } else {
            localStorage.removeItem(STORAGE_KEY);
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLoginEmailPersistence);
} else {
    initLoginEmailPersistence();
}
