(function ($) {
    'use strict';

    $(function () {
        const $form = $('#plnt-upsell-form');
        const $groups = $('#plnt-upsell-groups');
        const template = document.getElementById('plnt-upsell-group-template');
        const $status = $('#plnt-upsell-save-status');
        const $sync = $('#plnt-upsell-sync-all');
        let pendingDeletion = false;
        let syncing = false;
        let saving = false;
        let applying = 0;
        let nextIndex = 0;

        if (!$form.length || !$groups.length || !template) {
            return;
        }

        function rows() {
            return $groups.children('.plnt-upsell-group');
        }

        function setCollapsed($group, collapsed) {
            $group.toggleClass('is-collapsed', collapsed);
            $group.find('.plnt-upsell-group__toggle')
                .attr('aria-expanded', String(!collapsed))
                .attr('aria-label', collapsed ? 'Развернуть группу' : 'Свернуть группу');
        }

        function isDirty() {
            return pendingDeletion || rows().filter('.is-dirty').length > 0;
        }

        function updateDirtyStatus() {
            const dirty = isDirty();
            $status.toggleClass('is-dirty', dirty)
                .text(dirty ? 'Есть несохранённые изменения' : 'Все изменения сохранены');
            $sync.prop('disabled', dirty || syncing || saving);

            if (dirty) {
                $('#plnt-upsell-sync-result').text('Сначала сохраните настройки');
            } else {
                $('#plnt-upsell-sync-result').text('');
            }
        }

        function markDirty($group) {
            $group.addClass('is-dirty');
            $group.find('.plnt-upsell-group__save-result').text('Есть несохранённые изменения');
            updateDirtyStatus();
        }

        function hasUnappliedSkus($group) {
            const $input = $group.find('.plnt-upsell-group__skus');
            const value = $input.val().trim();
            return Boolean(value && value !== ($input.data('lastAppliedSkus') || ''));
        }

        function refreshTerms() {
            const occupied = new Map();

            rows().each(function () {
                const value = $(this).find('.plnt-upsell-group__term').val();

                if (value) {
                    occupied.set(value, this);
                }
            });

            rows().each(function () {
                const $select = $(this).find('.plnt-upsell-group__term');
                const selected = $select.val();

                $select.find('option').each(function () {
                    if (!this.value) {
                        return;
                    }

                    const owner = occupied.get(this.value);
                    const taken = Boolean(owner && owner !== $select.closest('.plnt-upsell-group')[0]);
                    this.disabled = taken;
                    this.dataset.originalLabel = this.dataset.originalLabel || this.textContent;
                    this.textContent = this.dataset.originalLabel + (taken ? ' — уже используется' : '');
                });

                const name = $select.find('option:selected').text().trim();
                $(this).find('.plnt-upsell-group__title').text(selected ? name : 'Новая группа');
            });
        }

        function refreshSelected($group) {
            const $items = $group.find('.plnt-upsell-group__item');
            const $list = $group.find('.plnt-upsell-group__list');
            const $toggle = $group.find('.plnt-upsell-group__show-all');
            const filter = $group.find('.plnt-upsell-group__selected-filter').val().trim().toLocaleLowerCase('ru');
            const expanded = $group.data('expanded') === true;
            let matched = 0;

            $items.each(function () {
                const matches = !filter || this.textContent.toLocaleLowerCase('ru').includes(filter);

                if (matches) {
                    matched += 1;
                }

                this.hidden = !matches || (!filter && !expanded && matched > 8);
            });

            $list.toggleClass('is-expanded', expanded || Boolean(filter));
            $group.find('.plnt-upsell-group__total').text($items.length);
            $group.find('.plnt-upsell-group__summary-total').text($items.length);
            $group.find('.plnt-upsell-group__empty')
                .prop('hidden', $items.length > 0)
                .text('Горшки пока не выбраны.');

            $toggle.prop('hidden', Boolean(filter) || $items.length <= 8)
                .text(expanded ? 'Свернуть список' : 'Показать все (' + $items.length + ')');
        }

        function makeSelectedItem(product, index) {
            const item = document.createElement('li');
            item.className = 'plnt-upsell-group__item';
            item.dataset.productId = String(product.id);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'upsell_groups[' + index + '][products][]';
            input.value = String(product.id);

            const title = document.createElement('span');
            title.className = 'plnt-upsell-group__item-title';

            const link = document.createElement('a');
            link.textContent = product.name;
            link.href = product.editUrl || '#';
            link.target = '_blank';
            link.rel = 'noopener noreferrer';
            title.appendChild(link);

            if (product.sku) {
                const sku = document.createElement('small');
                sku.textContent = 'Арт. ' + product.sku;
                title.appendChild(sku);
            }

            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'plnt-upsell-group__item-remove';
            remove.textContent = '×';
            remove.setAttribute('aria-label', 'Убрать ' + product.name);

            item.append(input, title, remove);
            return item;
        }

        function selectedIds($group) {
            return new Set($group.find('.plnt-upsell-group__item').map(function () {
                return String(this.dataset.productId);
            }).get());
        }

        async function applySkus($group) {
            const $input = $group.find('.plnt-upsell-group__skus');
            const $button = $group.find('.plnt-upsell-group__apply');
            const $result = $group.find('.plnt-upsell-group__apply-result');
            const $errors = $group.find('.plnt-upsell-group__sku-errors');
            const $errorBox = $group.find('.plnt-upsell-group__errors');
            const raw = $input.val().trim();

            if (syncing || saving) {
                $result.text('Дождитесь завершения сохранения.');
                return;
            }

            if (!raw) {
                $result.text('Введите артикулы через запятую.');
                return;
            }

            applying += 1;
            $button.prop('disabled', true);
            $result.text('Проверяем артикулы…');
            $errors.empty();
            $errorBox.prop('hidden', true);

            try {
                const response = await $.ajax({
                    url: plntPlantUpsellAdmin.ajaxUrl,
                    type: 'POST',
                    dataType: 'json',
                    timeout: 60000,
                    data: {
                        action: 'plnt_plant_upsell_resolve_skus',
                        nonce: plntPlantUpsellAdmin.nonce,
                        skus: raw,
                    },
                });

                if (!response.success) {
                    throw new Error(response.data && response.data.message || 'Не удалось проверить артикулы.');
                }

                const products = response.data.products || [];
                const unresolved = response.data.unresolved || [];
                const selected = selectedIds($group);
                const field = $group.find('.plnt-upsell-group__term').attr('name');
                const index = field.match(/^upsell_groups\[([^\]]+)\]/)[1];
                let added = 0;
                let existing = 0;

                products.forEach(function (product) {
                    if (selected.has(String(product.id))) {
                        existing += 1;
                        return;
                    }

                    $group.find('.plnt-upsell-group__list').append(makeSelectedItem(product, index));
                    selected.add(String(product.id));
                    added += 1;
                });

                unresolved.forEach(function (item) {
                    const row = document.createElement('li');
                    row.textContent = item.sku + ' — ' + item.reason;
                    $errors.append(row);
                });

                $errorBox.prop('hidden', !unresolved.length);
                $group.find('.plnt-upsell-group__error-count').text(unresolved.length);
                $input.val(unresolved.map((item) => item.sku).join(', '));
                $input.data('lastAppliedSkus', $input.val().trim());
                $group.find('.plnt-upsell-group__selected-filter').val('');
                $group.data('expanded', true);
                refreshSelected($group);

                if (added) {
                    markDirty($group);
                }

                $result.text('Добавлено: ' + added + '. Уже выбрано: ' + existing +
                    '. Не найдено или недоступно: ' + unresolved.length + '.');
            } catch (error) {
                $result.text(error.responseJSON && error.responseJSON.data && error.responseJSON.data.message
                    || error.message || 'Не удалось проверить артикулы.');
            } finally {
                applying -= 1;
                $button.prop('disabled', false);
            }
        }

        async function saveGroup($group) {
            if (syncing || saving || applying) {
                $group.find('.plnt-upsell-group__save-result').text('Дождитесь завершения обработки артикулов.');
                return;
            }

            const $term = $group.find('.plnt-upsell-group__term');
            const termId = $term.val();
            const $result = $group.find('.plnt-upsell-group__save-result');

            if (!termId) {
                $result.text('Сначала выберите значение атрибута.');
                $term.trigger('focus');
                return;
            }

            if (hasUnappliedSkus($group)) {
                $result.text('Нажмите «Применить» для новых артикулов перед сохранением.');
                return;
            }

            saving = true;
            const $button = $group.find('.plnt-upsell-group__save');
            const $controls = $group.find('.plnt-upsell-group__term, .plnt-upsell-group__skus, ' +
                '.plnt-upsell-group__apply, .plnt-upsell-group__item-remove, .plnt-upsell-group__delete');
            $button.prop('disabled', true);
            $controls.prop('disabled', true);
            $result.text('Сохраняем группу…');

            try {
                const productIds = $group.find('.plnt-upsell-group__item').map(function () {
                    return this.dataset.productId;
                }).get();

                const response = await $.ajax({
                    url: plntPlantUpsellAdmin.ajaxUrl,
                    type: 'POST',
                    dataType: 'json',
                    timeout: 60000,
                    data: {
                        action: 'plnt_plant_upsell_save_group',
                        nonce: plntPlantUpsellAdmin.nonce,
                        original_term_id: $group.attr('data-saved-term-id') || 0,
                        term_id: termId,
                        products: productIds,
                    },
                });

                if (!response.success) {
                    throw new Error(response.data && response.data.message || 'Не удалось сохранить группу.');
                }

                const allowed = new Set((response.data.productIds || []).map(String));
                $group.find('.plnt-upsell-group__item').each(function () {
                    if (!allowed.has(this.dataset.productId)) {
                        $(this).remove();
                    }
                });

                $group.attr('data-saved-term-id', String(response.data.termId));
                $group.removeClass('is-dirty');
                refreshSelected($group);
                $result.text('Группа сохранена.' + (response.data.skipped
                    ? ' Недоступные товары пропущены: ' + response.data.skipped + '.'
                    : ' Ошибочные артикулы не добавлены.'));
            } catch (error) {
                $result.text(error.responseJSON && error.responseJSON.data && error.responseJSON.data.message
                    || error.message || 'Не удалось сохранить группу.');
            } finally {
                saving = false;
                $button.prop('disabled', false);
                $controls.prop('disabled', false);
                updateDirtyStatus();
            }
        }

        $groups.on('change', '.plnt-upsell-group__term', function () {
            refreshTerms();
            markDirty($(this).closest('.plnt-upsell-group'));
        });

        $groups.on('click', '.plnt-upsell-group__toggle', function () {
            const $group = $(this).closest('.plnt-upsell-group');
            setCollapsed($group, !$group.hasClass('is-collapsed'));
        });

        // Если обязательный атрибут не выбран, раскрываем группу перед подсказкой браузера.
        $form[0].addEventListener('invalid', function (event) {
            const $group = $(event.target).closest('.plnt-upsell-group');

            if ($group.length) {
                setCollapsed($group, false);
            }
        }, true);

        $groups.on('click', '.plnt-upsell-group__apply', function () {
            applySkus($(this).closest('.plnt-upsell-group'));
        });

        $groups.on('click', '.plnt-upsell-group__save', function () {
            saveGroup($(this).closest('.plnt-upsell-group'));
        });

        $groups.on('click', '.plnt-upsell-group__item-remove', function () {
            const $group = $(this).closest('.plnt-upsell-group');
            $(this).closest('.plnt-upsell-group__item').remove();
            refreshSelected($group);
            markDirty($group);
        });

        $groups.on('input', '.plnt-upsell-group__selected-filter', function () {
            refreshSelected($(this).closest('.plnt-upsell-group'));
        });

        $groups.on('click', '.plnt-upsell-group__show-all', function () {
            const $group = $(this).closest('.plnt-upsell-group');
            $group.data('expanded', $group.data('expanded') !== true);
            refreshSelected($group);
        });

        $('#plnt-upsell-add-group').on('click', function () {
            nextIndex += 1;
            const index = 'new_' + Date.now() + '_' + nextIndex;
            const $group = $(template.innerHTML.replace(/__INDEX__/g, index));
            $groups.append($group);
            refreshTerms();
            refreshSelected($group);
            markDirty($group);
            $group.find('.plnt-upsell-group__term').trigger('focus');
        });

        $groups.on('click', '.plnt-upsell-group__delete', function () {
            if (!window.confirm('Удалить эту группу апсейлов?')) {
                return;
            }

            const $group = $(this).closest('.plnt-upsell-group');

            if (Number($group.attr('data-saved-term-id'))) {
                pendingDeletion = true;
            }

            $group.remove();
            refreshTerms();
            updateDirtyStatus();
        });

        $form.on('submit', function (event) {
            if (syncing || saving || applying) {
                event.preventDefault();
                window.alert('Дождитесь завершения текущей операции.');
                return;
            }

            if (rows().filter(function () {
                return hasUnappliedSkus($(this));
            }).length) {
                event.preventDefault();
                window.alert('Есть новые неприменённые артикулы. Нажмите «Применить» или очистите поле перед сохранением. Ошибки уже проверенных артикулов не препятствуют сохранению.');
                return;
            }

            const used = new Set();
            let duplicate = false;

            rows().find('.plnt-upsell-group__term').each(function () {
                if (used.has(this.value)) {
                    duplicate = true;
                }

                used.add(this.value);
            });

            if (duplicate) {
                event.preventDefault();
                window.alert('Для одного значения атрибута можно создать только одну группу.');
            }
        });

        async function syncAll() {
            if (isDirty() || syncing || saving || applying || !window.confirm(
                'Перезаписать апсейлы у всех комнатных растений по сохранённым группам?'
            )) {
                return;
            }

            syncing = true;
            $sync.prop('disabled', true);
            const $result = $('#plnt-upsell-sync-result');
            $result.text('Обновление апсейлов…');

            try {
                let offset = 0;
                let done = false;
                let skipped = 0;

                while (!done) {
                    const response = await $.ajax({
                        url: plntPlantUpsellAdmin.ajaxUrl,
                        type: 'POST',
                        dataType: 'json',
                        timeout: 60000,
                        data: {
                            action: 'plnt_plant_upsell_sync_all',
                            nonce: plntPlantUpsellAdmin.nonce,
                            offset,
                        },
                    });

                    if (!response.success) {
                        throw new Error(response.data && response.data.message || 'Ошибка обновления');
                    }

                    offset = response.data.processed;
                    done = response.data.done;
                    skipped += response.data.skipped || 0;
                    $result.text('Проверено: ' + offset + ' из ' + response.data.total +
                        '. Без настроенной группы пропущено: ' + skipped + '.');
                }

            } catch (error) {
                $result.text(error.message || 'Не удалось обновить апсейлы');
            } finally {
                syncing = false;
                $sync.prop('disabled', isDirty());
            }
        }

        $sync.on('click', function () {
            syncAll();
        });

        refreshTerms();
        rows().each(function () {
            refreshSelected($(this));
        });

        updateDirtyStatus();

    });
})(jQuery);
