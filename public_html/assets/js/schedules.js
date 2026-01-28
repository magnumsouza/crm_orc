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
    const quoteSelect = form.querySelector('[data-quote-select]');
    const quoteWrap = form.querySelector('[data-quote-wrap]');
    const modeSelect = form.querySelector('[data-schedule-mode]');
    const clientSelect = form.querySelector('[data-client-select]');
    const serviceField = form.querySelector('[data-service-description]');
    const notesField = form.querySelector('[data-notes]');

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

    const clearItems = () => {
        if (!container) {
            return;
        }
        container.querySelectorAll('[data-item-row]').forEach((row) => row.remove());
        const empty = container.querySelector('[data-empty-items]');
        if (empty) {
            empty.classList.remove('hidden');
        }
    };

    const addItemRow = (item) => {
        if (!container || !template) {
            return;
        }
        const empty = container.querySelector('[data-empty-items]');
        if (empty) {
            empty.classList.add('hidden');
        }
        const clone = document.importNode(template.content, true);
        const row = clone.querySelector('[data-item-row]');
        if (row) {
            const select = row.querySelector('[data-item-select]');
            const quantityInput = row.querySelector('input[name="items[][quantity]"]');
            if (select && item.inventory_id) {
                select.value = String(item.inventory_id);
            }
            if (quantityInput && item.quantity) {
                quantityInput.value = String(item.quantity);
            }
            bindRow(row);
        }
        container.appendChild(clone);
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

    const applyQuote = (quoteId) => {
        if (!window.scheduleQuotes || !Array.isArray(window.scheduleQuotes)) {
            return;
        }
        const quote = window.scheduleQuotes.find((item) => String(item.id) === String(quoteId));
        if (!quote) {
            return;
        }

        if (clientSelect && quote.client_id) {
            clientSelect.value = String(quote.client_id);
        }

        if (serviceField) {
            const defaultService = `Orcamento #${quote.id}`;
            if (!serviceField.value) {
                serviceField.value = defaultService;
            } else if (!serviceField.value.includes(defaultService)) {
                serviceField.value = `${serviceField.value} | ${defaultService}`;
            }
        }

        const serviceItems = (quote.items || []).filter((item) => item.item_type === 'servico');
        if (serviceItems.length > 0 && (notesField || serviceField)) {
            const summary = serviceItems
                .map((item) => {
                    const name = item.service_name || item.description || 'Servico';
                    const qty = item.quantity || 1;
                    return `${name} (${qty}x)`;
                })
                .join('; ');
            const noteLine = `Servicos do orcamento #${quote.id}: ${summary}`;
            if (notesField) {
                if (!notesField.value.includes(noteLine)) {
                    notesField.value = [notesField.value, noteLine].filter(Boolean).join('\n');
                }
            } else if (serviceField && !serviceField.value.includes(noteLine)) {
                serviceField.value = `${serviceField.value} | ${noteLine}`;
            }
        }

        const productItems = (quote.items || []).filter((item) => item.item_type === 'produto' && item.inventory_id);
        clearItems();
        productItems.forEach((item) => {
            addItemRow({
                inventory_id: item.inventory_id,
                quantity: item.quantity || 1,
            });
        });
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

    if (quoteSelect) {
        quoteSelect.addEventListener('change', () => {
            if (!quoteSelect.value) {
                return;
            }
            applyQuote(quoteSelect.value);
        });
    }

    const syncMode = (shouldClear) => {
        if (!modeSelect) {
            return;
        }
        const isQuote = modeSelect.value === 'orcamento';
        if (quoteWrap) {
            quoteWrap.classList.toggle('hidden', !isQuote);
        }
        if (quoteSelect) {
            quoteSelect.disabled = !isQuote || quoteSelect.getAttribute('data-disabled') === 'true';
            if (!isQuote) {
                quoteSelect.value = '';
            }
        }
        if (!isQuote && shouldClear) {
            if (clientSelect) {
                clientSelect.value = '';
            }
            if (serviceField) {
                serviceField.value = '';
            }
            if (notesField) {
                notesField.value = '';
            }
            clearItems();
        }
    };

    if (quoteSelect && quoteSelect.disabled) {
        quoteSelect.setAttribute('data-disabled', 'true');
    }
    if (modeSelect) {
        modeSelect.addEventListener('change', () => syncMode(true));
        syncMode(false);
    }
});
