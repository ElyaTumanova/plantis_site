/**
 * Plantis — «Сигналы»: открытие и сохранение сокращённой формы «Свойства».
 * Сохранение товара выполняется на сервере через WooCommerce CRUD.
 */
(function () {
    'use strict';

    document.querySelectorAll('.plnt-signals-restart-backfill').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('Повторно обработать всю историю заказов? Расчёт может быть длительным.')) {
                event.preventDefault();
            }
        });
    });

    const table = document.querySelector('.plnt-signals-table');
    const notice = document.getElementById('plnt-signals-notice');

    if (!table || !notice || typeof plntSignalsAdmin === 'undefined') {
        return;
    }

    const showNotice = (message, isError = false) => {
        const box = document.createElement('div');
        const paragraph = document.createElement('p');

        box.className = `notice ${isError ? 'notice-error' : 'notice-success'}`;
        paragraph.textContent = message;
        box.appendChild(paragraph);
        notice.replaceChildren(box);
    };

    const request = async (action, formData = new FormData()) => {
        formData.set('action', action);
        formData.set('nonce', plntSignalsAdmin.nonce);

        const response = await fetch(plntSignalsAdmin.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData,
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result?.data?.message || 'Не удалось выполнить запрос.');
        }

        return result.data;
    };

    const closeEditor = () => {
        table.querySelector('.plnt-signals-editor-row')?.remove();
        table.querySelectorAll('.plnt-signals-edit[aria-expanded="true"]').forEach((button) => {
            button.setAttribute('aria-expanded', 'false');
        });
    };

    const updateStockField = (form) => {
        const stock = form.elements.stock_quantity;
        const enabled = form.elements.manage_stock.checked;

        stock.disabled = !enabled;
        stock.required = enabled;
    };

    table.addEventListener('click', async (event) => {
        if (event.target.closest('.plnt-signals-close')) {
            closeEditor();
            return;
        }

        const discountButton = event.target.closest('.plnt-signals-discount-action');
        if (discountButton) {
            const operation = discountButton.dataset.operation;
            const message = operation === 'apply'
                ? `Применить акционную цену ${discountButton.dataset.newPrice} к товару?`
                : 'Удалить акционную цену и вернуть обычную цену товара?';

            if (!window.confirm(message)) return;

            discountButton.disabled = true;
            try {
                const data = new FormData();
                data.set('product_id', discountButton.dataset.productId);
                data.set('operation', operation);
                await request('plnt_signals_discount_action', data);
                closeEditor();

                // Изменение цены может изменить ценовую группу и ABC других товаров.
                window.location.reload();
                return;
            } catch (error) {
                showNotice(error.message, true);
            } finally {
                discountButton.disabled = false;
            }
            return;
        }

        const button = event.target.closest('.plnt-signals-edit');
        if (!button) {
            return;
        }

        if (button.getAttribute('aria-expanded') === 'true') {
            closeEditor();
            return;
        }

        closeEditor();

        const row = button.closest('tr.plnt-signals-row');
        const editor = document.createElement('tr');
        const cell = document.createElement('td');

        editor.className = 'plnt-signals-editor-row';
        cell.className = 'plnt-signals-editor-cell';
        cell.colSpan = Number(plntSignalsAdmin.columns) || 5;
        cell.textContent = 'Загрузка свойств…';
        editor.appendChild(cell);
        row.after(editor);

        button.setAttribute('aria-expanded', 'true');
        button.disabled = true;

        try {
            const data = new FormData();
            data.set('product_id', button.dataset.productId);
            const result = await request('plnt_signals_get_properties', data);

            if (editor.isConnected) {
                cell.innerHTML = result.html;
                const form = editor.querySelector('.plnt-signals-properties');
                if (form) {
                    updateStockField(form);
                    form.querySelector('input[name="name"]')?.focus();
                }
            }
        } catch (error) {
            if (editor.isConnected) editor.remove();
            button.setAttribute('aria-expanded', 'false');
            showNotice(error.message, true);
        } finally {
            button.disabled = false;
        }
    });

    table.addEventListener('change', (event) => {
        if (event.target.matches('input[name="manage_stock"]')) {
            const form = event.target.closest('.plnt-signals-properties');
            if (form) updateStockField(form);
        }
    });

    table.addEventListener('submit', async (event) => {
        const form = event.target.closest('form.plnt-signals-properties');
        if (!form) return;

        event.preventDefault();
        if (!form.reportValidity()) return;

        const submit = form.querySelector('button[type="submit"]');
        const errorBox = form.querySelector('.plnt-signals-error');
        errorBox.textContent = '';
        submit.disabled = true;
        form.classList.add('is-saving');

        try {
            const result = await request('plnt_signals_save_properties', new FormData(form));
            const row = table.querySelector(`tr.plnt-signals-row[data-product-id="${form.dataset.productId}"]`);
            closeEditor();

            const params = new URLSearchParams(window.location.search);
            const needsReload = result.refresh_demand
                || params.get('class_filter')
                || params.get('price_filter')
                || params.get('recommendation_filter')
                || params.get('stock_status_filter')
                || ['class', 'trend'].includes(params.get('orderby'));

            if (needsReload) {
                window.location.reload();
                return;
            }

            if (row) {
                if (result.row) {
                    row.outerHTML = result.row;
                } else {
                    row.remove();
                }
            }

            showNotice(result.message || 'Товар обновлён.');
        } catch (error) {
            errorBox.textContent = error.message;
        } finally {
            submit.disabled = false;
            form.classList.remove('is-saving');
        }
    });
})();
