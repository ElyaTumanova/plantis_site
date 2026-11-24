(function ($) {
    'use strict';

    $(function () {
        const $form = $('#plnt-links-settings-form');
        const $groups = $('#plnt-link-groups');
        const template = document.getElementById('plnt-link-group-template');
        const $saveStatus = $('#plnt-links-save-status');

        if (!$form.length || !$groups.length || !template) {
            return;
        }

        let dirty = false;
        let syncing = false;
        let rowCounter = 0;

        function rows() {
            return $groups.children('.plnt-link-group');
        }

        function markDirty() {
            dirty = true;
            $saveStatus.addClass('is-dirty').text('Есть несохранённые изменения');
            rows().find('.plnt-link-group__sync').prop('disabled', true);
            rows().find('.plnt-link-group__result').text('Сначала сохраните настройки');
        }

        function groupName(row) {
            return $(row).find('.plnt-link-group__name').val().trim() || 'Без названия';
        }

        function ownerOfTerm(select, termId) {
            const currentRow = select.closest('.plnt-link-group');
            const taxonomy = select.dataset.taxonomy;
            let owner = '';

            rows().each(function () {
                if (this === currentRow) {
                    return;
                }

                const other = this.querySelector('select[data-taxonomy="' + taxonomy + '"]');

                if (other && Array.from(other.selectedOptions).some(function (option) {
                    return option.value === String(termId);
                })) {
                    owner = groupName(this);
                    return false;
                }
            });

            return owner;
        }

        function getWidget(select) {
            const next = select.nextElementSibling;
            return next && next.classList.contains('plnt-multi') ? next : null;
        }

        function closeDropdowns(except) {
            $groups.find('.plnt-multi.is-open').each(function () {
                if (this === except) {
                    return;
                }

                this.classList.remove('is-open');
                this.querySelector('.plnt-multi__menu').hidden = true;
                this.querySelector('.plnt-multi__search').setAttribute('aria-expanded', 'false');
            });
        }

        function renderSelected(select) {
            const widget = getWidget(select);

            if (!widget) {
                return;
            }

            const selected = widget.querySelector('.plnt-multi__selected');
            selected.replaceChildren();

            Array.from(select.selectedOptions).forEach(function (option) {
                const chip = document.createElement('span');
                chip.className = 'plnt-multi__chip';

                const title = document.createElement('span');
                title.className = 'plnt-multi__chip-title';
                title.textContent = option.dataset.label || option.textContent;
                title.title = title.textContent;

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'plnt-multi__remove';
                remove.dataset.value = option.value;
                remove.textContent = '×';
                remove.setAttribute('aria-label', 'Убрать: ' + title.textContent);

                chip.append(title, remove);
                selected.appendChild(chip);
            });
        }

        function renderOptions(select) {
            const widget = getWidget(select);

            if (!widget) {
                return;
            }

            const input = widget.querySelector('.plnt-multi__search');
            const list = widget.querySelector('.plnt-multi__menu');
            const query = input.value.trim().toLocaleLowerCase('ru');
            const limit = 80;
            let count = 0;
            let total = 0;

            list.replaceChildren();

            Array.from(select.options).forEach(function (option) {
                const label = option.dataset.label || option.textContent;

                if (option.selected || (query && !label.toLocaleLowerCase('ru').includes(query))) {
                    return;
                }

                total += 1;

                if (count >= limit) {
                    return;
                }

                count += 1;
                const occupiedBy = ownerOfTerm(select, option.value);
                const item = document.createElement('button');
                item.type = 'button';
                item.className = 'plnt-multi__option';
                item.dataset.value = option.value;
                item.disabled = Boolean(occupiedBy);
                item.setAttribute('role', 'option');
                item.setAttribute('aria-selected', 'false');

                const title = document.createElement('span');
                title.textContent = label;
                item.appendChild(title);

                if (occupiedBy) {
                    const note = document.createElement('small');
                    note.textContent = 'Группа «' + occupiedBy + '»';
                    item.appendChild(note);
                }

                list.appendChild(item);
            });

            if (!total) {
                const empty = document.createElement('div');
                empty.className = 'plnt-multi__empty';
                empty.textContent = query ? 'Ничего не найдено' : 'Все доступные значения выбраны';
                list.appendChild(empty);
            } else if (total > limit) {
                const hint = document.createElement('div');
                hint.className = 'plnt-multi__empty';
                hint.textContent = 'Ещё результаты — уточните поиск';
                list.appendChild(hint);
            }
        }

        function refreshGroups() {
            rows().each(function () {
                $(this).find('.plnt-link-group__heading-title').text(groupName(this));

                $(this).find('select.plnt-link-group__terms').each(function () {
                    const select = this;

                    Array.from(select.options).forEach(function (option) {
                        option.disabled = Boolean(ownerOfTerm(select, option.value));
                    });

                    renderSelected(select);
                    renderOptions(select);
                });
            });
        }

        function initSelects($scope) {
            $scope.find('select.plnt-link-group__terms').each(function () {
                const select = this;

                if (getWidget(select)) {
                    return;
                }

                const widget = document.createElement('div');
                widget.className = 'plnt-multi';

                const selected = document.createElement('div');
                selected.className = 'plnt-multi__selected';

                const input = document.createElement('input');
                input.type = 'search';
                input.className = 'plnt-multi__search';
                input.placeholder = select.dataset.placeholder || 'Поиск...';
                input.autocomplete = 'off';
                input.setAttribute('role', 'combobox');
                input.setAttribute('aria-autocomplete', 'list');
                input.setAttribute('aria-expanded', 'false');

                const list = document.createElement('div');
                list.className = 'plnt-multi__menu';
                list.setAttribute('role', 'listbox');
                list.hidden = true;

                widget.append(selected, input, list);
                select.insertAdjacentElement('afterend', widget);
                select.classList.add('plnt-is-enhanced');
            });
        }

        function openDropdown(widget) {
            closeDropdowns(widget);
            widget.classList.add('is-open');
            widget.querySelector('.plnt-multi__menu').hidden = false;
            widget.querySelector('.plnt-multi__search').setAttribute('aria-expanded', 'true');
            renderOptions(widget.previousElementSibling);
        }

        $groups.on('focus', '.plnt-multi__search', function () {
            openDropdown(this.closest('.plnt-multi'));
        });

        $groups.on('input', '.plnt-multi__search', function () {
            renderOptions(this.closest('.plnt-multi').previousElementSibling);
        });

        $groups.on('keydown', '.plnt-multi__search', function (event) {
            const widget = this.closest('.plnt-multi');

            if (event.key === 'Escape') {
                closeDropdowns();
                return;
            }

            if (event.key === 'Enter') {
                event.preventDefault();
                const first = widget.querySelector('.plnt-multi__option:not(:disabled)');

                if (first) {
                    first.click();
                }
            }
        });

        $groups.on('click', '.plnt-multi__option', function () {
            if (this.disabled) {
                return;
            }

            const widget = this.closest('.plnt-multi');
            const select = widget.previousElementSibling;
            const option = Array.from(select.options).find((item) => item.value === this.dataset.value);

            if (!option || ownerOfTerm(select, option.value)) {
                return;
            }

            option.selected = true;
            widget.querySelector('.plnt-multi__search').value = '';
            $(select).trigger('change');
            widget.querySelector('.plnt-multi__search').focus();
        });

        $groups.on('click', '.plnt-multi__remove', function (event) {
            event.preventDefault();
            event.stopPropagation();

            const widget = this.closest('.plnt-multi');
            const select = widget.previousElementSibling;
            const option = Array.from(select.options).find((item) => item.value === this.dataset.value);

            if (option) {
                option.selected = false;
                $(select).trigger('change');
            }
        });

        $(document).on('pointerdown', function (event) {
            if (!$(event.target).closest('.plnt-multi').length) {
                closeDropdowns();
            }
        });

        $groups.on('input', '.plnt-link-group__name', function () {
            refreshGroups();
            markDirty();
        });

        $groups.on('change', '.plnt-link-group__terms', function () {
            refreshGroups();
            markDirty();
        });

        $('#plnt-link-add-group').on('click', function () {
            rowCounter += 1;
            const key = 'new_' + Date.now() + '_' + rowCounter;
            const markup = template.innerHTML.replace(/__INDEX__/g, key);
            const $row = $(markup).filter('.plnt-link-group').first();

            $groups.append($row);
            initSelects($row);
            refreshGroups();
            markDirty();
            $row.find('.plnt-link-group__name').trigger('focus');
        });

        $groups.on('click', '.plnt-link-group__remove', function () {
            if (!window.confirm('Удалить эту группу из настроек?')) {
                return;
            }

            $(this).closest('.plnt-link-group').remove();
            refreshGroups();
            markDirty();
        });

        $groups.sortable({
            items: '> .plnt-link-group',
            handle: '.plnt-link-group__handle',
            placeholder: 'plnt-link-group__placeholder',
            tolerance: 'pointer',
            update: function () {
                closeDropdowns();
                refreshGroups();
                markDirty();
            },
        });

        $form.on('submit', function (event) {
            const used = {categories: new Set(), tags: new Set()};
            let duplicate = false;

            rows().find('select.plnt-link-group__terms').each(function () {
                const taxonomy = this.dataset.taxonomy;

                Array.from(this.selectedOptions).forEach(function (option) {
                    if (used[taxonomy].has(option.value)) {
                        duplicate = true;
                    }

                    used[taxonomy].add(option.value);
                });
            });

            if (duplicate) {
                event.preventDefault();
                window.alert('Категория или метка выбрана в нескольких группах.');
            }
        });

        $groups.on('click', '.plnt-link-group__sync', async function () {
            if (dirty || syncing) {
                return;
            }

            const $row = $(this).closest('.plnt-link-group');
            const groupId = String($row.data('group-id') || '');
            const $result = $row.find('.plnt-link-group__result');

            if (!groupId) {
                return;
            }

            if (!window.confirm('Перезаписать кроссейлы у всех растений группы «' + groupName($row) + '»? Это массовое обновление связей товаров.')) {
                return;
            }

            syncing = true;
            rows().find('.plnt-link-group__sync').prop('disabled', true);

            try {
                let offset = 0;
                let done = false;

                while (!done) {
                    const response = await $.ajax({
                        url: plntProductLinksSettings.ajaxUrl,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'plnt_plant_cross_sell_sync_group',
                            nonce: plntProductLinksSettings.nonce,
                            group_id: groupId,
                            offset,
                        },
                    });

                    if (!response.success) {
                        throw new Error(response.data && response.data.message || 'Ошибка обновления');
                    }

                    offset = response.data.processed;
                    done = response.data.done;
                    $result.text('Обновлено: ' + offset + ' из ' + response.data.total);

                    if (done) {
                        $row.find('.plnt-link-group__count').text('Растений в группе: ' + response.data.total);
                    }
                }
            } catch (error) {
                $result.text(error.message || 'Не удалось обновить группу');
            } finally {
                syncing = false;

                if (!dirty) {
                    rows().each(function () {
                        const $row = $(this);
                        $row.find('.plnt-link-group__sync').prop('disabled', !$row.data('group-id'));
                    });
                }
            }
        });

        initSelects($groups);
        refreshGroups();
    });
})(jQuery);
