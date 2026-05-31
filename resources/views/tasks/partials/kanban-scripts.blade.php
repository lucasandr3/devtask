<script>
    function allowDrop(event) {
        event.preventDefault();
        event.currentTarget.classList.add('ring-2', 'ring-primary-500', 'ring-opacity-50');
    }

    function dragLeave(event) {
        event.currentTarget.classList.remove('ring-2', 'ring-primary-500', 'ring-opacity-50');
    }

    function dragStart(event) {
        const card = event.target.closest('.kanban-card');
        if (!card) return;
        event.dataTransfer.setData('taskId', card.dataset.taskId);
        card.classList.add('opacity-50');
    }

    function dragEnd(event) {
        const card = event.target.closest('.kanban-card');
        if (card) card.classList.remove('opacity-50');
    }

    async function dropTask(event) {
        event.preventDefault();
        event.currentTarget.classList.remove('ring-2', 'ring-primary-500', 'ring-opacity-50');

        const taskId = event.dataTransfer.getData('taskId');
        const newStatus = event.currentTarget.dataset.status;
        const taskCard = document.querySelector(`[data-task-id="${taskId}"]`);

        if (!taskCard) return;

        const emptyMessage = event.currentTarget.querySelector('.kanban-empty');
        if (emptyMessage) emptyMessage.remove();
        event.currentTarget.appendChild(taskCard);

        try {
            const response = await fetch(`/tarefas/${taskId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status: newStatus })
            });

            if (!response.ok) throw new Error('Erro ao atualizar status');
            updateColumnCounts();
        } catch (error) {
            window.location.reload();
        }
    }

    function updateColumnCounts() {
        document.querySelectorAll('.kanban-column-body').forEach(column => {
            const count = column.querySelectorAll('.kanban-card').length;
            const header = column.previousElementSibling;
            const countBadge = header?.querySelector('span:last-child');
            if (countBadge) countBadge.textContent = count;

            const hasEmpty = column.querySelector('.kanban-empty');
            if (count === 0 && !hasEmpty) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'kanban-empty text-center py-8 text-gray-400 dark:text-muted-foreground text-sm';
                emptyDiv.textContent = 'Nenhuma tarefa';
                column.appendChild(emptyDiv);
            }
        });
    }
</script>
