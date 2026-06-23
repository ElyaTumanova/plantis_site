<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'product_cat_add_form_fields', 'plnt_add_product_cat_front_image_field' );
function plnt_add_product_cat_front_image_field() {
    ?>
    <div class="form-field">
        <label for="front_image">Front image</label>

        <input type="hidden" name="front_image" id="front_image" value="">

        <div id="front_image_preview"></div>

        <button type="button" class="button plnt-upload-front-image">
            Выбрать изображение
        </button>

        <button type="button" class="button plnt-remove-front-image">
            Удалить
        </button>
    </div>
    <?php
}

add_action( 'product_cat_edit_form_fields', 'plnt_edit_product_cat_front_image_field' );
function plnt_edit_product_cat_front_image_field( $term ) {
    $image_id = get_term_meta( $term->term_id, 'front_image', true );
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="front_image">Front image</label>
        </th>
        <td>
            <input type="hidden" name="front_image" id="front_image" value="<?php echo esc_attr( $image_id ); ?>">

            <div id="front_image_preview">
                <?php
                if ( $image_id ) {
                    echo wp_get_attachment_image( $image_id, 'thumbnail' );
                }
                ?>
            </div>

            <button type="button" class="button plnt-upload-front-image">
                Выбрать изображение
            </button>

            <button type="button" class="button plnt-remove-front-image">
                Удалить
            </button>
        </td>
    </tr>
    <?php
}

add_action( 'created_product_cat', 'plnt_save_product_cat_front_image' );
add_action( 'edited_product_cat', 'plnt_save_product_cat_front_image' );

function plnt_save_product_cat_front_image( $term_id ) {
    if ( isset( $_POST['front_image'] ) ) {
        update_term_meta(
            $term_id,
            'front_image',
            absint( $_POST['front_image'] )
        );
    }
}

add_action( 'admin_enqueue_scripts', 'plnt_product_cat_front_image_admin_scripts' );
function plnt_product_cat_front_image_admin_scripts( $hook ) {
    if ( $hook !== 'edit-tags.php' && $hook !== 'term.php' ) {
        return;
    }

    wp_enqueue_media();

    wp_add_inline_script( 'jquery-core', "
        jQuery(function($) {
            let frame;

            $(document).on('click', '.plnt-upload-front-image', function(e) {
                e.preventDefault();

                frame = wp.media({
                    title: 'Выберите изображение',
                    button: { text: 'Использовать' },
                    multiple: false
                });

                frame.on('select', function() {
                    const attachment = frame.state().get('selection').first().toJSON();

                    $('#front_image').val(attachment.id);
                    $('#front_image_preview').html('<img src=\"' + attachment.sizes.thumbnail.url + '\" style=\"max-width:150px;height:auto;\" />');
                });

                frame.open();
            });

            $(document).on('click', '.plnt-remove-front-image', function(e) {
                e.preventDefault();

                $('#front_image').val('');
                $('#front_image_preview').html('');
            });
        });
    " );
}

add_action( 'product_cat_add_form_fields', 'plnt_add_product_cat_catalog_image_field' );
function plnt_add_product_cat_catalog_image_field() {
	?>
	<div class="form-field">
		<label for="catalog_image">Catalog image</label>

		<input type="hidden" name="catalog_image" id="catalog_image" value="">

		<div id="catalog_image_preview"></div>

		<button type="button" class="button plnt-upload-catalog-image">
			Выбрать изображение
		</button>

		<button type="button" class="button plnt-remove-catalog-image">
			Удалить
		</button>
	</div>
	<?php
}

add_action( 'product_cat_edit_form_fields', 'plnt_edit_product_cat_catalog_image_field' );
function plnt_edit_product_cat_catalog_image_field( $term ) {
	$image_id = get_term_meta( $term->term_id, 'catalog_image', true );
	?>
	<tr class="form-field">
		<th scope="row">
			<label for="catalog_image">Catalog image</label>
		</th>
		<td>
			<input
				type="hidden"
				name="catalog_image"
				id="catalog_image"
				value="<?php echo esc_attr( $image_id ); ?>"
			>

			<div id="catalog_image_preview">
				<?php
				if ( $image_id ) {
					echo wp_get_attachment_image( $image_id, 'thumbnail' );
				}
				?>
			</div>

			<button type="button" class="button plnt-upload-catalog-image">
				Выбрать изображение
			</button>

			<button type="button" class="button plnt-remove-catalog-image">
				Удалить
			</button>
		</td>
	</tr>
	<?php
}

add_action( 'created_product_cat', 'plnt_save_product_cat_catalog_image' );
add_action( 'edited_product_cat', 'plnt_save_product_cat_catalog_image' );

function plnt_save_product_cat_catalog_image( $term_id ) {
	if ( isset( $_POST['catalog_image'] ) ) {
		update_term_meta(
			$term_id,
			'catalog_image',
			absint( $_POST['catalog_image'] )
		);
	}
}

add_action( 'admin_enqueue_scripts', 'plnt_product_cat_catalog_image_admin_scripts' );
function plnt_product_cat_catalog_image_admin_scripts( $hook ) {

	if ( $hook !== 'edit-tags.php' && $hook !== 'term.php' ) {
		return;
	}

	wp_enqueue_media();

	wp_add_inline_script( 'jquery-core', "
		jQuery(function($) {

			$(document).on('click', '.plnt-upload-catalog-image', function(e) {
				e.preventDefault();

				const frame = wp.media({
					title: 'Выберите изображение',
					button: { text: 'Использовать' },
					multiple: false
				});

				frame.on('select', function() {
					const attachment = frame.state().get('selection').first().toJSON();

					$('#catalog_image').val(attachment.id);
					$('#catalog_image_preview').html(
						'<img src=\"' + attachment.sizes.thumbnail.url + '\" style=\"max-width:150px;height:auto;\" />'
					);
				});

				frame.open();
			});

			$(document).on('click', '.plnt-remove-catalog-image', function(e) {
				e.preventDefault();

				$('#catalog_image').val('');
				$('#catalog_image_preview').html('');
			});

		});
	" );
}



// Front image для меток
add_action( 'product_tag_add_form_fields', 'plnt_add_product_cat_front_image_field' );
add_action( 'product_tag_edit_form_fields', 'plnt_edit_product_cat_front_image_field' );
add_action( 'created_product_tag', 'plnt_save_product_cat_front_image' );
add_action( 'edited_product_tag', 'plnt_save_product_cat_front_image' );

// Catalog image для меток
add_action( 'product_tag_add_form_fields', 'plnt_add_product_cat_catalog_image_field' );
add_action( 'product_tag_edit_form_fields', 'plnt_edit_product_cat_catalog_image_field' );
add_action( 'created_product_tag', 'plnt_save_product_cat_catalog_image' );
add_action( 'edited_product_tag', 'plnt_save_product_cat_catalog_image' );