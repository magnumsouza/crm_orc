document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[data-slots-url]');
    if (!form) {
        return;
    }

    const slotsUrl = form.getAttribute('data-slots-url');
    const dateInput = form.querySelector('[data-schedule-date]');
    const timeSelect = form.querySelector('[data-schedule-time]');
    const message = form.querySelector('[data-slots-message]');

    const container = form.querySelector('[data-items-container]');
    const addButton = form.querySelector('[data-add-item]');
    const template = document.getElementById('scheduleItemTemplate');

    const updateStockLabel = (row) => {
        const select = row.querySelector('[data-item-select]');
        const label = row.querySelector('[data-stock-label]');
        if (!select || !label) {
            return;
        }
        const option = select.options[select.selectedIndex];
        const available = option ? option.getAttribute('data-available') : '';
        if (available) {
            label.textContent = `Disponivel: ${available}`;
        } else {
            label.textContent = '';
        }
    };

    const bindRow = (row) => {
        const removeBtn = row.querySelector('[data-remove-item]');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                row.remove();
                const empty = container.querySelector('[data-empty-items]');
                if (empty && container.querySelectorAll('[data-item-row]').length === 0) {
                    empty.classList.remove('hidden');
                }
            });
        }
        const select = row.querySelector('[data-item-select]');
        if (select) {
            select.addEventListener('change', () => updateStockLabel(row));
            updateStockLabel(row);
        }
    };

    const populateSlots = (slots) => {
        const current = timeSelect.getAttribute('data-current-time');
        timeSelect.innerHTML = '';
        if (!slots || slots.length === 0) {
            const option = document.createElement('option');
            option.textContent = 'Sem horarios disponiveis';
            option.value = '';
            timeSelect.appendChild(option);
            timeSelect.disabled = true;
            if (message) {
                message.textContent = 'Sem horarios para esta data.';
            }
            return;
        }
        timeSelect.disabled = false;
        slots.forEach((slot) => {
            const option = document.createElement('option');
            option.value = `${slot}:00`;
            option.textContent = slot;
            if (current && current === slot) {
                option.selected = true;
            }
            timeSelect.appendChild(option);
        });
        if (current && !slots.includes(current)) {
            const option = document.createElement('option');
            option.value = `${current}:00`;
            option.textContent = `${current} (atual)`;
            option.selected = true;
            timeSelect.appendChild(option);
        }
        if (message) {
            message.textContent = 'Horarios carregados.';
        }
    };

    const loadSlots = () => {
        const date = dateInput ? dateInput.value : '';
        if (!date || !timeSelect) {
            return;
        }
        timeSelect.disabled = true;
        if (message) {
            message.textContent = 'Carregando horarios...';
        }
        fetch(`${slotsUrl}&date=${encodeURIComponent(date)}`)
            .then((response) => response.json())
            .then((data) => populateSlots(data.slots || []))
            .catch(() => populateSlots([]));
    };

    if (dateInput) {
        dateInput.addEventListener('change', loadSlots);
        if (dateInput.value) {
            loadSlots();
        }
    }

    if (container) {
        container.querySelectorAll('[data-item-row]').forEach(bindRow);
    }

    if (addButton && container && template) {
        addButton.addEventListener('click', () => {
            const empty = container.querySelector('[data-empty-items]');
            if (empty) {
                empty.classList.add('hidden');
            }
            const clone = document.importNode(template.content, true);
            const row = clone.querySelector('[data-item-row]');
            if (row) {
                bindRow(row);
            }
            container.appendChild(clone);
        });
    }
});
