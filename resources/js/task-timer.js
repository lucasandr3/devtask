function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
}

async function parseJsonResponse(response) {
    const contentType = response.headers.get('content-type') ?? '';

    if (contentType.includes('application/json')) {
        return response.json();
    }

    return {
        success: false,
        message: 'Resposta inválida do servidor.',
    };
}

function formatElapsed(seconds) {
    const total = Math.max(0, Math.floor(Number(seconds) || 0));
    const hours = Math.floor(total / 3600);
    const minutes = Math.floor((total % 3600) / 60);
    const secs = total % 60;

    return [hours, minutes, secs]
        .map((value) => String(value).padStart(2, '0'))
        .join(':');
}

function updateTimerDisplays(data) {
    document.querySelectorAll('[data-task-timer-display]').forEach((element) => {
        const taskId = Number(element.dataset.taskId);
        const isActive = data?.active && Number(data.task?.id) === taskId;

        if (isActive) {
            element.textContent = formatElapsed(data.elapsed_seconds);
            element.classList.remove('hidden');
        } else if (element.dataset.mode === 'elapsed') {
            element.classList.add('hidden');
        }
    });

    document.querySelectorAll('[data-task-timer-toggle]').forEach((button) => {
        const taskId = Number(button.dataset.taskId);
        const isActive = data?.active && Number(data.task?.id) === taskId;
        const action = isActive ? 'stop' : 'start';

        button.dataset.action = action;
        button.title = '';
        button.dataset.tooltip = isActive ? 'Parar cronômetro' : 'Iniciar cronômetro';
        button.setAttribute('aria-label', button.dataset.tooltip);
        button.classList.toggle('text-red-600', isActive);
        button.classList.toggle('hover:bg-red-50', isActive);
        button.classList.toggle('dark:hover:bg-red-900/20', isActive);
        button.classList.toggle('text-green-600', !isActive);
        button.classList.toggle('hover:bg-green-50', !isActive);
        button.classList.toggle('dark:hover:bg-green-900/20', !isActive);

        const playIcon = button.querySelector('[data-icon="play"]');
        const stopIcon = button.querySelector('[data-icon="stop"]');

        if (playIcon) {
            playIcon.classList.toggle('hidden', isActive);
        }

        if (stopIcon) {
            stopIcon.classList.toggle('hidden', !isActive);
        }
    });

        if (data?.total_minutes_label && data?.task?.id) {
            document.querySelectorAll(`[data-task-total-minutes][data-task-id="${data.task.id}"]`).forEach((element) => {
                const suffix = element.dataset.totalSuffix ?? '';
                element.textContent = `${data.total_minutes_label}${suffix}`;
            });
        }
}

async function fetchActiveTimer() {
    const response = await fetch('/timer/ativo', {
        headers: {
            Accept: 'application/json',
        },
    });

    const data = await parseJsonResponse(response);

    if (!response.ok) {
        return { active: false };
    }

    return data;
}

async function refreshActiveTimer() {
    const data = await fetchActiveTimer();
    updateTimerDisplays(data);
    return data;
}

async function toggleTaskTimer(taskId, action) {
    const url = action === 'start'
        ? `/tarefas/${taskId}/timer/iniciar`
        : `/tarefas/${taskId}/timer/parar`;

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken(),
                Accept: 'application/json',
            },
        });

        const data = await parseJsonResponse(response);

        if (!response.ok || data.success === false) {
            window.Toast?.show(data.message || 'Erro no cronômetro', 'error');
            return null;
        }

        window.Toast?.show(data.message, 'success');

        const activeData = await refreshActiveTimer();

        const timerDetail = {
            ...activeData,
            task: { id: Number(taskId) },
        };

        if (data.total_minutes_label) {
            timerDetail.total_minutes_label = data.total_minutes_label;
        }

        window.dispatchEvent(new CustomEvent('timer-updated', { detail: timerDetail }));

        return data;
    } catch {
        window.Toast?.show('Erro ao processar cronômetro', 'error');
        return null;
    }
}

function handleTimerToggleClick(event) {
    event.preventDefault();
    event.stopPropagation();

    const button = event.currentTarget;
    const taskId = button.dataset.taskId;
    const action = button.dataset.action || 'start';

    toggleTaskTimer(taskId, action);
}

function initTaskTimerUi() {
    document.querySelectorAll('[data-task-timer-toggle]').forEach((button) => {
        button.addEventListener('click', handleTimerToggleClick);
    });

    refreshActiveTimer();

    window.addEventListener('timer-updated', (event) => {
        updateTimerDisplays(event.detail ?? { active: false });
    });

    setInterval(async () => {
        const data = await fetchActiveTimer();

        if (data.active) {
            updateTimerDisplays(data);
        }
    }, 1000);
}

window.toggleTaskTimer = toggleTaskTimer;
window.refreshActiveTimer = refreshActiveTimer;
window.formatTaskElapsed = formatElapsed;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTaskTimerUi);
} else {
    initTaskTimerUi();
}
