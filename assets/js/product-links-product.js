(function ($) {
    'use strict';

    $(function () {
        const $buttons = $('.plnt-plant-links-recalculate');

        if (!$buttons.length) {
            return;
        }

        $buttons.on('click', async function () {
            const $button = $(this);
            const kind = $button.data('kind');
            const $result = $('#plnt-plant-links-' + kind + '-result');
            $buttons.prop('disabled', true);
            $result.text({
                cross: 'Пересчитываем кросс-сейлы…',
                upsell: 'Пересчитываем апсейлы…',
                attributes: 'Обновляем атрибуты из категорий…',
            }[kind] || 'Обрабатываем…');

            try {
                const response = await $.ajax({
                    url: window.ajaxurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'plnt_plant_links_recalculate_product',
                        product_id: $button.data('product-id'),
                        nonce: $button.data('nonce'),
                        kind,
                    },
                });

                if (!response.success) {
                    throw new Error(response.data && response.data.message || 'Ошибка пересчёта.');
                }

                $result.text(response.data.message + ' Чтобы увидеть новые значения в полях WooCommerce, обновите страницу.');
            } catch (error) {
                const message = error.responseJSON && error.responseJSON.data && error.responseJSON.data.message;
                $result.text(message || error.message || 'Не удалось обновить связи.');
            } finally {
                $buttons.prop('disabled', false);
            }
        });
    });
})(jQuery);
