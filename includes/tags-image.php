<?php
// === 1. Поля на странице добавления/редактирования метки ===

// Добавление поля на форме "добавить" метку
add_action('product_tag_add_form_fields', function() {
    ?>
    <div class="form-field term-image-wrap">
        <label for="product_tag_thumb_id"><?php _e('Изображение', 'plantis-theme'); ?></label>
        <input type="hidden" id="product_tag_thumb_id" name="product_tag_thumb_id" value="">
        <div id="product_tag_thumb_preview" style="margin-bottom:10px;"></div>
        <button type="button" class="button upload_product_tag_image"><?php _e('Загрузить/выбрать', 'plantis-theme'); ?></button>
        <button type="button" class="button remove_product_tag_image" style="display:none;"><?php _e('Удалить', 'plantis-theme'); ?></button>
        <p class="description"><?php _e('Будет использоваться как изображение метки (аналогично категории).', 'plantis-theme'); ?></p>
    </div>
    <?php
});

// Поле на форме "редактировать" метку
add_action('product_tag_edit_form_fields', function($term) {
    $image_id = (int) get_term_meta($term->term_id, 'thumbnail_id', true);
    $image_html = $image_id ? wp_get_attachment_image($image_id, [150,150]) : '';
    ?>
    <tr class="form-field term-image-wrap">
        <th scope="row"><label for="product_tag_thumb_id"><?php _e('Изображение', 'plantis-theme'); ?></label></th>
        <td>
            <input type="hidden" id="product_tag_thumb_id" name="product_tag_thumb_id" value="<?php echo esc_attr($image_id); ?>">
            <div id="product_tag_thumb_preview" style="margin-bottom:10px;"><?php echo $image_html; ?></div>
            <button type="button" class="button upload_product_tag_image"><?php _e('Загрузить/выбрать', 'plantis-theme'); ?></button>
            <button type="button" class="button remove_product_tag_image" <?php if (!$image_id) echo 'style="display:none;"'; ?>><?php _e('Удалить', 'plantis-theme'); ?></button>
            <p class="description"><?php _e('Будет использоваться как изображение метки (аналогично категории).', 'plantis-theme'); ?></p>
        </td>
    </tr>
    <?php
});

// Сохранение при создании/редактировании
add_action('created_product_tag', 'save_product_tag_thumb_meta');
add_action('edited_product_tag',  'save_product_tag_thumb_meta');
function save_product_tag_thumb_meta($term_id) {
    if (isset($_POST['product_tag_thumb_id'])) {
        $image_id = (int) $_POST['product_tag_thumb_id'];
        if ($image_id) {
            update_term_meta($term_id, 'thumbnail_id', $image_id); // как у категорий
        } else {
            delete_term_meta($term_id, 'thumbnail_id');
        }
    }
}

// Подключаем медиабиблиотеку и JS в админке только на страницах меток товара
add_action('admin_enqueue_scripts', function($hook){
    // edit-tags.php – список терминов; term.php – редактирование термина
    if ( ( $hook === 'edit-tags.php' || $hook === 'term.php' )
      && isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'product_tag' ) {
        wp_enqueue_media();
        wp_add_inline_script('jquery-core', "
            jQuery(function($){
                var frame;
                function openFrame(){
                    if (frame) { frame.open(); return; }
                    frame = wp.media({
                        title: '".esc_js(__('Выберите изображение', 'plantis-theme'))."',
                        button: { text: '".esc_js(__('Использовать', 'plantis-theme'))."' },
                        multiple: false
                    });
                    frame.on('select', function(){
                        var attachment = frame.state().get('selection').first().toJSON();
                        $('#product_tag_thumb_id').val(attachment.id);
                        $('#product_tag_thumb_preview').html('<img src=\"'+attachment.sizes.thumbnail.url+'\" style=\"max-width:150px;height:auto;\"/>');
                        $('.remove_product_tag_image').show();
                    });
                    frame.open();
                }
                $(document).on('click', '.upload_product_tag_image', function(e){
                    e.preventDefault();
                    openFrame();
                });
                $(document).on('click', '.remove_product_tag_image', function(e){
                    e.preventDefault();
                    $('#product_tag_thumb_id').val('');
                    $('#product_tag_thumb_preview').empty();
                    $(this).hide();
                });
            });
        ");
    }
});
