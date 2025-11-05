<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Поле «Синонимы» у товара (meta _synonyms)

// Метабокс
add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'plnt_synonyms_box',
        'Синонимы (поиск)',
        function ( $post ) {
            $val = get_post_meta( $post->ID, '_synonyms', true );
            echo '<textarea style="width:100%;min-height:120px" name="plnt_synonyms">'.esc_textarea($val).'</textarea>';
            echo '<p class="description">Перечислите синонимы через запятую. По ним тоже будет идти поиск.</p>';
            wp_nonce_field( 'plnt_synonyms_save', 'plnt_synonyms_nonce' );
        },
        'product',
        'normal',
        'default'
    );
});

// Сохранение
add_action( 'save_post_product', function ( $post_id ) {
    if ( ! isset($_POST['plnt_synonyms_nonce']) || ! wp_verify_nonce( $_POST['plnt_synonyms_nonce'], 'plnt_synonyms_save' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $val = isset($_POST['plnt_synonyms']) ? wp_kses_post( wp_unslash($_POST['plnt_synonyms']) ) : '';
    update_post_meta( $post_id, '_synonyms', $val );
});

//Поле «Синонимы» у категории товара (term meta _synonyms)

// Поле на экране добавления категории
add_action( 'product_cat_add_form_fields', function () {
    ?>
    <div class="form-field">
        <label for="plnt_cat_synonyms">Синонимы (поиск)</label>
        <textarea id="plnt_cat_synonyms" name="plnt_cat_synonyms" rows="5" cols="40"></textarea>
        <p class="description">Перечислите синонимы через запятую. По ним тоже будет идти поиск.</p>
    </div>
    <?php
});

// Поле на экране редактирования категории
add_action( 'product_cat_edit_form_fields', function ( $term ) {
    $val = get_term_meta( $term->term_id, '_synonyms', true );
    ?>
    <tr class="form-field">
        <th scope="row"><label for="plnt_cat_synonyms">Синонимы (поиск)</label></th>
        <td>
            <textarea id="plnt_cat_synonyms" name="plnt_cat_synonyms" rows="5" cols="50"><?php echo esc_textarea( $val ); ?></textarea>
            <p class="description">Перечислите синонимы через запятую.</p>
        </td>
    </tr>
    <?php
});

// Сохранение
add_action( 'created_product_cat', function ( $term_id ) {
    if ( isset($_POST['plnt_cat_synonyms']) ) {
        update_term_meta( $term_id, '_synonyms', wp_kses_post( wp_unslash($_POST['plnt_cat_synonyms']) ) );
    }
});
add_action( 'edited_product_cat', function ( $term_id ) {
    if ( isset($_POST['plnt_cat_synonyms']) ) {
        update_term_meta( $term_id, '_synonyms', wp_kses_post( wp_unslash($_POST['plnt_cat_synonyms']) ) );
    }
});

