import Swal from 'sweetalert2';

function isDarkMode() {
    return document.documentElement.classList.contains('dark');
}

function themeColors() {
    const styles = getComputedStyle(document.documentElement);

    return {
        background: styles.getPropertyValue('--card').trim()
            ? `hsl(${styles.getPropertyValue('--card').trim()})`
            : undefined,
        color: styles.getPropertyValue('--card-foreground').trim()
            ? `hsl(${styles.getPropertyValue('--card-foreground').trim()})`
            : undefined,
        primary: styles.getPropertyValue('--primary').trim()
            ? `hsl(${styles.getPropertyValue('--primary').trim()})`
            : '#2563eb',
        muted: styles.getPropertyValue('--muted').trim()
            ? `hsl(${styles.getPropertyValue('--muted').trim()})`
            : '#6b7280',
    };
}

function baseConfig() {
    const colors = themeColors();

    return {
        background: colors.background,
        color: colors.color,
        confirmButtonColor: colors.primary,
        buttonsStyling: true,
        customClass: {
            popup: 'gestorpro-swal-popup',
            title: 'gestorpro-swal-title',
            htmlContainer: 'gestorpro-swal-html',
            confirmButton: 'gestorpro-swal-confirm',
            cancelButton: 'gestorpro-swal-cancel',
        },
    };
}

const toastMixin = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    },
});

function fireToast(message, type = 'success', duration = 4000) {
    return toastMixin.fire({
        ...baseConfig(),
        icon: type,
        title: message,
        timer: duration,
    });
}

function fireModal(options) {
    return Swal.fire({
        ...baseConfig(),
        ...options,
    });
}

window.SwalAlert = {
    success(message, title = 'Sucesso!') {
        return fireModal({
            icon: 'success',
            title,
            text: message,
            confirmButtonText: 'OK',
        });
    },

    error(message, title = 'Erro!') {
        const isHtml = String(message).includes('<');

        return fireModal({
            icon: 'error',
            title,
            ...(isHtml ? { html: message } : { text: message }),
            confirmButtonText: 'OK',
        });
    },

    warning(message, title = 'Atenção!') {
        return fireModal({
            icon: 'warning',
            title,
            text: message,
            confirmButtonText: 'OK',
        });
    },

    info(message, title = 'Informação') {
        return fireModal({
            icon: 'info',
            title,
            text: message,
            confirmButtonText: 'OK',
        });
    },

    confirm(options = {}) {
        return fireModal({
            icon: options.icon || 'warning',
            title: options.title || 'Confirmar ação',
            text: options.text || 'Deseja continuar?',
            showCancelButton: true,
            confirmButtonText: options.confirmButtonText || 'Sim, confirmar',
            cancelButtonText: options.cancelButtonText || 'Cancelar',
            reverseButtons: true,
            focusCancel: true,
            ...options,
        });
    },
};

window.Toast = {
    show(message, type = 'success', duration = 4000) {
        return fireToast(message, type, duration);
    },
};

function initConfirmForms() {
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        if (form.dataset.confirmBound === 'true') {
            return;
        }

        form.dataset.confirmBound = 'true';

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const result = await window.SwalAlert.confirm({
                title: form.dataset.confirmTitle || 'Confirmar ação',
                text: form.dataset.confirm,
                icon: form.dataset.confirmIcon || 'warning',
                confirmButtonText: form.dataset.confirmButton || 'Sim, confirmar',
                cancelButtonText: form.dataset.cancelButton || 'Cancelar',
            });

            if (result.isConfirmed) {
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });
}

function escapeHtml(value) {
    const element = document.createElement('div');
    element.textContent = value;
    return element.innerHTML;
}

function initFlashMessages() {
    document.querySelectorAll('[data-flash-payload]').forEach((element) => {
        try {
            const payload = JSON.parse(element.dataset.flashPayload);

            if (payload?.message) {
                const type = payload.type || 'success';
                const titles = {
                    success: 'Sucesso!',
                    error: 'Erro!',
                    warning: 'Atenção!',
                    info: 'Informação',
                };

                window.SwalAlert[type]?.(payload.message, titles[type] ?? 'Aviso');
            }
        } catch {
            // ignore invalid payload
        }

        element.remove();
    });

    const errorsElement = document.getElementById('flash-errors');

    if (errorsElement?.dataset.errors) {
        try {
            const errors = JSON.parse(errorsElement.dataset.errors);

            if (errors.length === 1) {
                window.SwalAlert.error(errors[0]);
            } else if (errors.length > 1) {
                window.SwalAlert.error(
                    `<ul style="text-align:left;margin:0;padding-left:1.25rem;">${errors.map((error) => `<li>${escapeHtml(error)}</li>`).join('')}</ul>`,
                    'Ocorreram erros'
                );
            }
        } catch {
            window.SwalAlert.error('Ocorreram erros ao processar a solicitação.');
        }

        errorsElement.remove();
    }
}

function initNotifyListener() {
    window.addEventListener('notify', (event) => {
        const detail = event.detail || {};
        window.Toast.show(detail.message || 'Operação concluída.', detail.type || 'success');
    });
}

function init() {
    initConfirmForms();
    initFlashMessages();
    initNotifyListener();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

export default Swal;
