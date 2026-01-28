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
            const price = parseFloat(row.querySelector('.price-input')?.value || '0');
            const qty = parseInt(row.querySelector('.qty-input')?.value || '0', 10);
            total += price * qty;
        });
        if (totalValue) {
            totalValue.textContent = formatMoney(total);
        }
    };

    const toggleType = (row, type) => {
        const productField = row.querySelector('.product-field');
        const serviceField = row.querySelector('.service-field');
        const productSelect = row.querySelector('.product-select');
        const serviceSelect = row.querySelector('.service-select');
        const serviceDesc = row.querySelector('.service-desc');
        const priceInput = row.querySelector('.price-input');

        if (type === 'servico') {
            productField?.classList.add('hidden');
            serviceField?.classList.remove('hidden');
            if (productSelect) productSelect.required = false;
            if (serviceSelect) serviceSelect.required = false;
            if (serviceDesc) serviceDesc.required = true;
            if (priceInput) {
                priceInput.required = true;
                priceInput.readOnly = false;
                priceInput.classList.remove('bg-slate-50');
            }
        } else {
            productField?.classList.remove('hidden');
            serviceField?.classList.add('hidden');
            if (productSelect) productSelect.required = true;
            if (serviceSelect) serviceSelect.required = false;
            if (serviceDesc) serviceDesc.required = false;
            if (priceInput) {
                priceInput.required = false;
                priceInput.readOnly = true;
                priceInput.classList.add('bg-slate-50');
            }
        }
    };

    const addItem = (preset) => {
        const clone = template.content.cloneNode(true);
        const row = clone.querySelector('.item-row');
        if (!row) {
            return;
        }

        const typeSelect = row.querySelector('.item-type');
        const productSelect = row.querySelector('.product-select');
        const serviceSelect = row.querySelector('.service-select');
        const serviceDesc = row.querySelector('.service-desc');
        const qtyInput = row.querySelector('.qty-input');
        const priceInput = row.querySelector('.price-input');
        const removeBtn = row.querySelector('.remove-item');

        const updateProduct = () => {
            if (!productSelect || !priceInput) return;
            const option = productSelect.options[productSelect.selectedIndex];
            const price = parseFloat(option?.dataset?.price || '0');
            priceInput.value = price.toFixed(2);
            recalc();
        };

        const updateServiceFromSelect = () => {
            if (!serviceSelect) return;
            const option = serviceSelect.options[serviceSelect.selectedIndex];
            const price = parseFloat(option?.dataset?.price || '0');
            const name = option?.dataset?.name || '';
            const desc = option?.dataset?.desc || '';

            if (priceInput && (priceInput.dataset.auto !== '0')) {
                priceInput.value = price > 0 ? price.toFixed(2) : '';
                priceInput.dataset.auto = '1';
            }
            if (serviceDesc && (serviceDesc.dataset.auto !== '0')) {
                serviceDesc.value = desc || name;
                serviceDesc.dataset.auto = '1';
            }
            recalc();
        };

        typeSelect?.addEventListener('change', () => {
            const type = typeSelect.value || 'produto';
            toggleType(row, type);
            if (type === 'produto') {
                updateProduct();
            } else {
                updateServiceFromSelect();
            }
            recalc();
        });

        productSelect?.addEventListener('change', updateProduct);
        serviceSelect?.addEventListener('change', updateServiceFromSelect);

        priceInput?.addEventListener('input', () => {
            priceInput.dataset.auto = '0';
            recalc();
        });

        serviceDesc?.addEventListener('input', () => {
            serviceDesc.dataset.auto = '0';
        });

        qtyInput?.addEventListener('input', recalc);

        removeBtn?.addEventListener('click', () => {
            row.remove();
            recalc();
            renameInputs();
        });

        container.appendChild(row);

        if (preset) {
            const presetType = preset.item_type || 'produto';
            if (typeSelect) typeSelect.value = presetType;
            toggleType(row, presetType);
            if (presetType === 'servico') {
                if (serviceSelect && preset.service_id) {
                    serviceSelect.value = preset.service_id.toString();
                }
                if (serviceDesc) {
                    serviceDesc.value = preset.description || '';
                    serviceDesc.dataset.auto = '0';
                }
                if (priceInput) {
                    priceInput.value = parseFloat(preset.unit_price || 0).toFixed(2);
                    priceInput.dataset.auto = '0';
                }
            } else {
                if (productSelect) {
                    productSelect.value = preset.inventory_id?.toString() || '';
                }
                updateProduct();
            }
            if (qtyInput) {
                qtyInput.value = preset.quantity?.toString() || '1';
            }
        } else {
            toggleType(row, 'produto');
            updateProduct();
        }

        renameInputs();
        recalc();
    };

    const renameInputs = () => {
        container.querySelectorAll('.item-row').forEach((row, index) => {
            const typeSelect = row.querySelector('.item-type');
            const productSelect = row.querySelector('.product-select');
            const serviceSelect = row.querySelector('.service-select');
            const serviceDesc = row.querySelector('.service-desc');
            const qty = row.querySelector('.qty-input');
            const price = row.querySelector('.price-input');

            if (typeSelect) typeSelect.name = `items[${index}][type]`;
            if (productSelect) productSelect.name = `items[${index}][inventory_id]`;
            if (serviceSelect) serviceSelect.name = `items[${index}][service_id]`;
            if (serviceDesc) serviceDesc.name = `items[${index}][description]`;
            if (qty) qty.name = `items[${index}][quantity]`;
            if (price) price.name = `items[${index}][unit_price]`;
        });
    };

    addButton.addEventListener('click', () => addItem());

    if (Array.isArray(window.quoteExistingItems) && window.quoteExistingItems.length) {
        container.innerHTML = '';
        window.quoteExistingItems.forEach((item) => addItem(item));
    } else {
        addItem();
    }

    window.quoteResetItems = () => {
        container.innerHTML = '';
        addItem();
    };
})();
