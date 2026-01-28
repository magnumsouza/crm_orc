(() => {
    const container = document.getElementById('items-container');
    const addButton = document.getElementById('add-item');
    const template = document.getElementById('item-template');
    const totalValue = document.getElementById('total-value');

    if (!container || !addButton || !template) {
        return;
    }

    const formatMoney = (value) => {
        return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    };

    const recalc = () => {
        let total = 0;
        container.querySelectorAll('.item-row').forEach((row) => {
            const price = parseFloat(row.dataset.price || '0');
            const qty = parseInt(row.querySelector('.qty-input').value || '0', 10);
            total += price * qty;
        });
        totalValue.textContent = formatMoney(total);
    };

    const addItem = (preset) => {
        const clone = template.content.cloneNode(true);
        const row = document.createElement('div');
        row.className = 'item-row';
        row.appendChild(clone);

        const select = row.querySelector('.product-select');
        const qtyInput = row.querySelector('.qty-input');
        const unitPrice = row.querySelector('.unit-price');
        const removeBtn = row.querySelector('.remove-item');

        const updateRow = () => {
            const option = select.options[select.selectedIndex];
            const price = parseFloat(option?.dataset?.price || '0');
            row.dataset.price = price.toString();
            unitPrice.textContent = formatMoney(price);
            recalc();
        };

        select.addEventListener('change', updateRow);
        qtyInput.addEventListener('input', recalc);
        removeBtn.addEventListener('click', () => {
            row.remove();
            recalc();
            renameInputs();
        });

        container.appendChild(row);
        if (preset) {
            select.value = preset.inventory_id?.toString() || '';
            qtyInput.value = preset.quantity?.toString() || '1';
        }
        updateRow();
        renameInputs();
    };

    const renameInputs = () => {
        container.querySelectorAll('.item-row').forEach((row, index) => {
            const select = row.querySelector('.product-select');
            const qty = row.querySelector('.qty-input');
            select.name = `items[${index}][inventory_id]`;
            qty.name = `items[${index}][quantity]`;
        });
    };

    addButton.addEventListener('click', addItem);

    if (Array.isArray(window.quoteExistingItems) && window.quoteExistingItems.length) {
        container.innerHTML = '';
        window.quoteExistingItems.forEach((item) => addItem(item));
    } else {
        addItem();
    }

    // Expose a reset hook for modal reuse
    window.quoteResetItems = () => {
        container.innerHTML = '';
        addItem();
    };
})();
