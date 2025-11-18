<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Поле «Синонимы» у товара (meta _synonyms)

// 1) Добавляем вкладку "Синонимы"
add_filter( 'woocommerce_product_data_tabs', function( $tabs ) {
    $tabs['plnt_synonyms_tab'] = [
        'label'    => 'Синонимы',
        'target'   => 'plnt_synonyms_panel',
        'class'    => ['show_if_simple','show_if_variable','show_if_grouped','show_if_external'],
        'priority' => 80,
    ];
    return $tabs;
} );

// 2) Панель с полем
add_action( 'woocommerce_product_data_panels', function() {
    echo '<div id="plnt_synonyms_panel" class="panel woocommerce_options_panel">';
    woocommerce_wp_textarea_input( [
        'id'          => '_synonyms',
        'label'       => 'Синонимы (поиск)',
        'description' => 'Перечислите синонимы через запятую. По ним тоже будет идти поиск.',
        'desc_tip'    => true,
        'rows'        => 6,
    ] );
    echo '</div>';
} );

// 3) Сохранение значения
add_action( 'woocommerce_admin_process_product_object', function( $product ) {
    if ( isset( $_POST['_synonyms'] ) ) {
        $val = wp_kses_post( wp_unslash( $_POST['_synonyms'] ) );
        $product->update_meta_data( '_synonyms', $val );
    }
} );


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

