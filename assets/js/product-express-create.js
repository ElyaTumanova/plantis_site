(function ($) {
    'use strict';

    $(function () {
        const config = window.plntExpressProduct;
        const $modal = $('#plnt-express-modal');
        const $form = $('#plnt-express-form');
        const $dialog = $modal.find('.plnt-express-modal__dialog');
        const $attributes = $('#plnt-express-attributes');
        const $categories = $('#plnt-express-categories');
        const $tags = $('#plnt-express-tags');
        const $error = $('#plnt-express-error');
        const $save = $('#plnt-express-save');
        const $next = $('#plnt-express-next');
        const $back = $('#plnt-express-back');
        const $importStatus = $('#plnt-express-import-result');
        const selectPlugin = $.fn.selectWoo ? 'selectWoo' : ($.fn.select2 ? 'select2' : null);
        const attributeOptionsCache = Object.create(null);
        let busy = false;
        let importing = false;
        let latestSkuRequest = 0;
        let currentStep = 1;
        let $openButton;

        if (!$modal.length || !config || !selectPlugin) {
            return;
        }

        // Основная кнопка рядом со стандартной «Добавить товар».
        $openButton = $('<button type="button" class="page-title-action">Добавить товар — экспресс</button>');
        const $addNew = $('.wrap .page-title-action').first();

        if ($addNew.length) {
            $addNew.after($openButton);
        } else {
            $('.wrap .wp-heading-inline').first().after($openButton);
        }

        function values($select) {
            return ($select.val() || []).map(Number).filter(Boolean);
        }

        function showError(message) {
            $error.empty().append($('<p/>').text(message)).prop('hidden', false);
        }

        function clearError() {
            $error.empty().prop('hidden', true);
        }

        function goToStep(step) {
            currentStep = step;
            clearError();

            $modal.find('select.select2-hidden-accessible').each(function () {
                $(this)[selectPlugin]('close');
            });

            $modal.find('.plnt-express-modal__step').each(function () {
                $(this).prop('hidden', Number($(this).data('step')) !== step);
            });

            $modal.find('.plnt-express-modal__step-indicator').each(function () {
                const active = Number($(this).data('step-indicator')) === step;
                $(this).toggleClass('is-active', active);
                if (active) {
                    $(this).attr('aria-current', 'step');
                } else {
                    $(this).removeAttr('aria-current');
                }
            });

            $next.prop('hidden', step !== 1);
            $back.prop('hidden', step !== 2);
            $save.prop('hidden', step !== 2);
            $('#plnt-express-footer-hint').text(step === 1
                ? 'Шаг 1 из 2. Товар будет создан со статусом «Личное».'
                : 'Шаг 2 из 2. Атрибуты необязательны.');
            $modal.find('.plnt-express-modal__content').scrollTop(0);
        }

        function validateBasics() {
            if (!String($('#plnt-express-name').val() || '').trim()) {
                showError('Укажите название товара.');
                $('#plnt-express-name').trigger('focus');
                return false;
            }

            const price = String($('#plnt-express-price').val() || '').trim().replace(',', '.');
            if (!/^\d+(\.\d+)?$/.test(price) || !Number.isFinite(Number(price))) {
                showError('Укажите корректную неотрицательную цену продажи.');
                $('#plnt-express-price').trigger('focus');
                return false;
            }

            const stock = document.getElementById('plnt-express-stock');
            if (stock && !stock.checkValidity()) {
                stock.reportValidity();
                return false;
            }

            if (!values($categories).length) {
                showError('Выберите хотя бы одну категорию.');
                $categories[selectPlugin]('open');
                return false;
            }

            clearError();
            return true;
        }

        function loadLatestSku() {
            const requestId = ++latestSkuRequest;
            const $reference = $('#plnt-express-last-sku');
            const $referenceName = $('#plnt-express-last-sku-name');

            $reference.val('').attr('placeholder', 'Загружаем…');
            $referenceName.text('');

            $.ajax({
                url: config.ajaxUrl,
                method: 'GET',
                dataType: 'json',
                data: {
                    action: 'plnt_express_last_sku',
                    nonce: config.nonce,
                },
            }).done(function (response) {
                if (requestId !== latestSkuRequest) {
                    return;
                }

                if (!response.success) {
                    $reference.attr('placeholder', 'Не удалось загрузить');
                    return;
                }

                const sku = response.data && response.data.sku || '';
                const name = response.data && response.data.name || '';
                $reference.val(sku).attr('placeholder', sku ? '' : 'Артикулы не найдены');
                $referenceName.text(name ? 'Товар: ' + name : '');
            }).fail(function () {
                if (requestId === latestSkuRequest) {
                    $reference.attr('placeholder', 'Не удалось загрузить');
                }
            });
        }

        function setBusy(state) {
            busy = state;
            $save.prop('disabled', state).text(state ? 'Сохраняем…' : 'Сохранить товар');
            $next.prop('disabled', state);
            $back.prop('disabled', state);
            $('#plnt-express-import-attributes, #plnt-express-add-attribute').prop('disabled', state);
            $form.find('input, select').prop('disabled', state);

            if (!state) {
                $attributes.find('.plnt-express-modal__attribute-terms').each(function () {
                    const $row = $(this).closest('.plnt-express-modal__attribute-row');
                    const taxonomy = $row.find('.plnt-express-modal__attribute-taxonomy').val();
                    $(this).prop('disabled', !taxonomy || !!$row.data('termsLoading'));
                });
            }
        }

        function openModal() {
            $modal.prop('hidden', false);
            $('body').addClass('plnt-express-open');
            $dialog.trigger('focus');
            $('#plnt-express-name').trigger('focus');
        }

        function closeModal() {
            if (busy || importing) {
                return;
            }

            $modal.prop('hidden', true);
            $('body').removeClass('plnt-express-open');
            if ($openButton) {
                $openButton.trigger('focus');
            }
        }

        function initializeTermSelect($select, taxonomy, placeholder) {
            $select[selectPlugin]({
                width: '100%',
                placeholder: placeholder,
                allowClear: false,
                dropdownParent: $modal,
                minimumInputLength: 0,
                ajax: {
                    url: config.ajaxUrl,
                    dataType: 'json',
                    delay: 250,
                    cache: true,
                    data: function (params) {
                        return {
                            action: 'plnt_express_search_terms',
                            nonce: config.nonce,
                            taxonomy: typeof taxonomy === 'function' ? taxonomy() : taxonomy,
                            search: params.term || '',
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response && response.success && response.data
                                ? response.data.results || []
                                : [],
                        };
                    },
                },
            });
        }

        initializeTermSelect($categories, 'product_cat', 'Поиск категорий…');
        initializeTermSelect($tags, 'product_tag', 'Поиск меток…');

        // Загружаем значения глобального атрибута один раз, затем SelectWoo ищет локально.
        function loadAttributeTerms($row, taxonomy) {
            const $terms = $row.find('.plnt-express-modal__attribute-terms');
            const $status = $row.find('.plnt-express-modal__term-status');
            const token = Number($row.data('termsToken') || 0) + 1;
            $row.data('termsToken', token);

            if (!taxonomy) {
                $row.data('termsLoading', false);
                $terms.prop('disabled', true);
                $status.text('');
                return;
            }

            $row.data('termsLoading', true);
            $terms.prop('disabled', true);
            $status.text('Загружаем значения…');

            if (!attributeOptionsCache[taxonomy]) {
                attributeOptionsCache[taxonomy] = $.ajax({
                    url: config.ajaxUrl,
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        action: 'plnt_express_search_terms',
                        nonce: config.nonce,
                        taxonomy: taxonomy,
                        search: '',
                        all: '1',
                    },
                }).then(function (response) {
                    if (!response.success) {
                        throw new Error(response.data && response.data.message || 'Ошибка загрузки значений.');
                    }
                    return response.data.results || [];
                });

                attributeOptionsCache[taxonomy].fail(function () {
                    delete attributeOptionsCache[taxonomy];
                });
            }

            attributeOptionsCache[taxonomy].done(function (options) {
                if ($row.data('termsToken') !== token || !$row.parent().length) {
                    return;
                }

                const selected = ($terms.val() || []).map(String);
                const selectedOptions = {};
                $terms.find('option:selected').each(function () {
                    selectedOptions[this.value] = this.text;
                });
                $terms.empty();

                options.forEach(function (item) {
                    const id = String(item.id);
                    $terms.append(new Option(item.text, id, false, selected.includes(id)));
                    delete selectedOptions[id];
                });

                Object.keys(selectedOptions).forEach(function (id) {
                    $terms.append(new Option(selectedOptions[id], id, false, true));
                });

                $row.data('termsLoading', false);
                $terms.prop('disabled', false).trigger('change');
                $status.text('');
            }).fail(function () {
                if ($row.data('termsToken') !== token || !$row.parent().length) {
                    return;
                }

                $row.data('termsLoading', false);
                $terms.prop('disabled', false);
                $status.text('Не удалось загрузить список. Смените атрибут и выберите его снова.');
            });
        }

        function renderAttributeOptions($select) {
            $select.append(new Option('Выберите атрибут', '', true, true));

            config.attributes.forEach(function (attribute) {
                $select.append(new Option(attribute.label, attribute.taxonomy));
            });
        }

        function makeAttributeRow(taxonomy) {
            const $row = $('<div class="plnt-express-modal__attribute-row"/>');
            const $attributeField = $('<div class="plnt-express-modal__field"/>');
            const $termField = $('<div class="plnt-express-modal__field"/>');
            const $taxonomy = $('<select class="plnt-express-modal__attribute-taxonomy" aria-label="Атрибут"/>');
            const $terms = $('<select class="plnt-express-modal__attribute-terms" multiple aria-label="Значения атрибута"/>');
            const $termStatus = $('<span class="description plnt-express-modal__term-status"/>');
            const $remove = $('<button type="button" class="plnt-express-modal__attribute-remove" aria-label="Удалить атрибут" title="Удалить атрибут"><svg viewBox="0 0 20 20" aria-hidden="true" focusable="false"><path d="M5 5l10 10M15 5L5 15" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg></button>');

            renderAttributeOptions($taxonomy);
            $attributeField.append($('<label/>').text('Атрибут'), $taxonomy);
            $termField.append($('<label/>').text('Значения'), $terms, $termStatus);
            $row.append($attributeField, $termField, $remove);
            $attributes.append($row);

            $taxonomy[selectPlugin]({
                width: '100%',
                dropdownParent: $modal,
                placeholder: 'Выберите атрибут',
            });

            $terms[selectPlugin]({
                width: '100%',
                dropdownParent: $modal,
                placeholder: 'Поиск значений…',
                closeOnSelect: false,
            });
            $terms.prop('disabled', true);

            if (taxonomy) {
                $taxonomy.val(taxonomy).trigger('change');
            }

            return $row;
        }

        function findAttributeRow(taxonomy) {
            return $attributes.children('.plnt-express-modal__attribute-row').filter(function () {
                return $(this).find('.plnt-express-modal__attribute-taxonomy').val() === taxonomy;
            }).first();
        }

        $attributes.on('change', '.plnt-express-modal__attribute-taxonomy', function () {
            const $row = $(this).closest('.plnt-express-modal__attribute-row');
            const taxonomy = $(this).val();
            const $other = findAttributeRow(taxonomy);

            if (taxonomy && $other.length && !$other.is($row)) {
                showError('Атрибут уже добавлен. Укажите дополнительные значения в существующей строке.');
                $(this).val('').trigger('change.select2');
                $row.find('.plnt-express-modal__attribute-terms').empty().trigger('change');
                loadAttributeTerms($row, '');
                return;
            }

            $row.find('.plnt-express-modal__attribute-terms').empty().trigger('change');
            loadAttributeTerms($row, taxonomy);
        });

        $attributes.on('click', '.plnt-express-modal__attribute-remove', function () {
            const $row = $(this).closest('.plnt-express-modal__attribute-row');
            $row.find('select').each(function () {
                $(this)[selectPlugin]('destroy');
            });
            $row.remove();
        });

        $('#plnt-express-add-attribute').on('click', function () {
            makeAttributeRow('');
        });

        $('#plnt-express-import-attributes').on('click', async function () {
            if (importing || busy) {
                return;
            }

            const categories = values($categories);

            if (!categories.length) {
                showError('Для загрузки атрибутов сначала выберите категории.');
                return;
            }

            clearError();
            importing = true;
            $save.prop('disabled', true);
            $back.prop('disabled', true);
            $importStatus.text('Получаем атрибуты…');
            $(this).prop('disabled', true);

            try {
                const response = await $.ajax({
                    url: config.ajaxUrl,
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'plnt_express_import_attributes',
                        nonce: config.nonce,
                        categories: categories,
                    },
                });

                if (!response.success) {
                    throw new Error(response.data && response.data.message || 'Не удалось загрузить атрибуты.');
                }

                if (categories.join(',') !== values($categories).join(',')) {
                    $importStatus.text('Категории изменились. Нажмите «Подтянуть» ещё раз.');
                    return;
                }

                const attributes = response.data.attributes || [];

                attributes.forEach(function (attribute) {
                    let $row = findAttributeRow(attribute.taxonomy);

                    if (!$row.length) {
                        $row = makeAttributeRow(attribute.taxonomy);
                    }

                    const $terms = $row.find('.plnt-express-modal__attribute-terms');
                    $terms.find('option').prop('selected', false);

                    attribute.terms.forEach(function (term) {
                        const $existing = $terms.find('option').filter(function () {
                            return this.value === String(term.id);
                        });

                        if ($existing.length) {
                            $existing.prop('selected', true);
                        } else {
                            $terms.append(new Option(term.text, term.id, false, true));
                        }
                    });

                    $terms.trigger('change');
                });

                $importStatus.text(attributes.length
                    ? 'Подтянуто атрибутов: ' + attributes.length + '. Значения можно изменить.'
                    : 'У выбранных категорий нет настроенных атрибутов.');
            } catch (error) {
                showError(error.responseJSON && error.responseJSON.data && error.responseJSON.data.message
                    || error.message || 'Ошибка загрузки атрибутов.');
                $importStatus.text('');
            } finally {
                importing = false;
                $save.prop('disabled', false);
                $back.prop('disabled', false);
                $(this).prop('disabled', false);
            }
        });

        function collectAttributes() {
            const result = {};

            $attributes.children('.plnt-express-modal__attribute-row').each(function () {
                const taxonomy = $(this).find('.plnt-express-modal__attribute-taxonomy').val();
                const ids = values($(this).find('.plnt-express-modal__attribute-terms'));

                if (taxonomy && ids.length) {
                    result[taxonomy] = Array.from(new Set((result[taxonomy] || []).concat(ids)));
                }
            });

            return result;
        }

        function resetForm() {
            $form[0].reset();
            loadLatestSku();
            $categories.val(null).trigger('change');
            $tags.val(null).trigger('change');

            $attributes.find('select').each(function () {
                $(this)[selectPlugin]('destroy');
            });
            $attributes.empty();
            $importStatus.text('');
            clearError();
            $('#plnt-express-success').prop('hidden', true);
            $form.prop('hidden', false);
            goToStep(1);
        }

        $next.on('click', function () {
            if (!busy && !importing && validateBasics()) {
                goToStep(2);
            }
        });

        $back.on('click', function () {
            if (!busy && !importing) {
                goToStep(1);
            }
        });

        $form.on('submit', async function (event) {
            event.preventDefault();

            if (busy || importing) {
                return;
            }

            if (currentStep === 1) {
                if (validateBasics()) {
                    goToStep(2);
                }
                return;
            }

            const categories = values($categories);

            if (!categories.length) {
                showError('Выберите хотя бы одну категорию.');
                return;
            }

            clearError();
            setBusy(true);

            try {
                const response = await $.ajax({
                    url: config.ajaxUrl,
                    method: 'POST',
                    dataType: 'json',
                    timeout: 60000,
                    data: {
                        action: 'plnt_express_create_product',
                        nonce: config.nonce,
                        name: $('#plnt-express-name').val(),
                        slug: $('#plnt-express-slug').val(),
                        sku: $('#plnt-express-sku').val(),
                        stock: $('#plnt-express-stock').val(),
                        price: $('#plnt-express-price').val(),
                        categories: categories,
                        tags: values($tags),
                        attributes: JSON.stringify(collectAttributes()),
                    },
                });

                if (!response.success) {
                    throw new Error(response.data && response.data.message || 'Не удалось создать товар.');
                }

                $('#plnt-express-edit-link').attr('href', response.data.editUrl);
                $('#plnt-express-success-message').text('Статус: «Личное». ' + response.data.message);
                $form.prop('hidden', true);
                $('#plnt-express-success').prop('hidden', false);
            } catch (error) {
                showError(error.responseJSON && error.responseJSON.data && error.responseJSON.data.message
                    || error.message || 'Ошибка сохранения товара.');
            } finally {
                setBusy(false);
            }
        });

        $openButton.on('click', function () {
            resetForm();
            openModal();
        });

        $('#plnt-express-add-another').on('click', function () {
            resetForm();
            $('#plnt-express-name').trigger('focus');
        });

        $modal.on('click', '.plnt-express-modal__close, .plnt-express-modal__cancel, .plnt-express-modal__backdrop', closeModal);

        $(document).on('keydown', function (event) {
            if (event.key === 'Escape' && !$modal.prop('hidden')) {
                closeModal();
            }
        });
    });
})(jQuery);
