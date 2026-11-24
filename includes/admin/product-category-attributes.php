<?php
/** Глобальные атрибуты WooCommerce в категориях комнатных растений. */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function plnt_category_attrs_is_plant( int $term_id ): bool {
    $root = get_term_by( 'slug', 'komnatnye-rasteniya', 'product_cat' );

    if ( ! $root || is_wp_error( $root ) ) {
        return false;
    }

    $root_id = (int) $root->term_id;

    return $term_id === $root_id
        || in_array( $root_id, array_map( 'intval', get_ancestors( $term_id, 'product_cat', 'taxonomy' ) ), true );
}

function plnt_category_attrs_get_attributes(): array {
    if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
        return array();
    }

    $attributes = array();

    foreach ( wc_get_attribute_taxonomies() as $attribute ) {
        $taxonomy = wc_attribute_taxonomy_name( $attribute->attribute_name );

        if ( taxonomy_exists( $taxonomy ) ) {
            $attributes[ $taxonomy ] = $attribute->attribute_label ?: $attribute->attribute_name;
        }
    }

    return $attributes;
}

add_action( 'admin_enqueue_scripts', 'plnt_category_attrs_enqueue_assets' );

function plnt_category_attrs_enqueue_assets( string $hook ): void {
    if ( 'term.php' !== $hook ) {
        return;
    }

    $screen = get_current_screen();

    if ( ! $screen || 'product_cat' !== $screen->taxonomy ) {
        return;
    }

    wp_enqueue_script( 'selectWoo' );
    wp_enqueue_style( 'woocommerce_admin_styles' );
}

add_action( 'product_cat_edit_form_fields', 'plnt_category_attrs_render' );

function plnt_category_attrs_render( $term ): void {
    if ( ! plnt_category_attrs_is_plant( (int) $term->term_id ) ) {
        return;
    }

    $saved = get_term_meta( $term->term_id, 'plnt_plant_category_attributes', true );
    $saved = is_array( $saved ) ? $saved : array();
    $data  = array();

    foreach ( plnt_category_attrs_get_attributes() as $taxonomy => $label ) {
        $terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false ) );
        $values = array();

        foreach ( is_wp_error( $terms ) ? array() : $terms as $value ) {
            $values[] = array( 'id' => (int) $value->term_id, 'name' => $value->name );
        }

        $data[ $taxonomy ] = array( 'label' => $label, 'values' => $values );
    }
    ?>
    <tr class="form-field">
        <th scope="row"><label>Атрибуты товаров</label></th>
        <td>
            <div id="plnt-category-attributes"></div>
            <p><button type="button" class="button" id="plnt-category-attribute-add">Добавить атрибут</button></p>
            <p class="description">Добавьте атрибут и выберите одно или несколько значений. Выбранные значения можно убрать крестиком.</p>
            <?php wp_nonce_field( 'plnt_category_attrs_save', 'plnt_category_attrs_nonce' ); ?>

            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const attributes = <?php echo wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>;
                const saved = <?php echo wp_json_encode( $saved, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>;
                const container = document.getElementById('plnt-category-attributes');
                const addButton = document.getElementById('plnt-category-attribute-add');
                let nextIndex = 0;

                const present = document.createElement('input');
                present.type = 'hidden';
                present.name = 'plnt_category_attrs_present';
                present.value = '1';
                container.appendChild(present);

                function addOption(select, value, label, selected = false) {
                    const option = new Option(label, value, false, selected);
                    select.add(option);
                }

                function addRow(taxonomy = '', selectedIds = []) {
                    const index = nextIndex++;
                    const row = document.createElement('div');
                    row.style.cssText = 'display:flex;flex-wrap:wrap;align-items:flex-start;gap:10px;margin-bottom:14px;';

                    const attrSelect = document.createElement('select');
                    attrSelect.name = 'plnt_category_attr_taxonomy[' + index + ']';
                    attrSelect.required = true;
                    attrSelect.style.cssText = 'width:250px;max-width:100%;min-height:36px;';
                    attrSelect.setAttribute('aria-label', 'Атрибут');
                    addOption(attrSelect, '', 'Выберите атрибут');

                    Object.entries(attributes).forEach(([key, item]) => {
                        addOption(attrSelect, key, item.label);
                    });

                    const valueSelect = document.createElement('select');
                    valueSelect.name = 'plnt_category_attr_term_ids[' + index + '][]';
                    valueSelect.multiple = true;
                    valueSelect.required = true;
                    valueSelect.style.cssText = 'width:100%;min-width:250px;';
                    valueSelect.setAttribute('aria-label', 'Значения атрибута');

                    const valuesWrap = document.createElement('div');
                    valuesWrap.style.cssText = 'flex:1 1 350px;min-width:250px;max-width:100%;';
                    valuesWrap.appendChild(valueSelect);

                    const remove = document.createElement('button');
                    remove.type = 'button';
                    remove.className = 'button-link-delete';
                    remove.textContent = 'Удалить';
                    remove.style.marginTop = '8px';
                    remove.addEventListener('click', () => row.remove());

                    function renderValues(selected) {
                        const current = attributes[attrSelect.value];
                        const selectedSet = new Set(selected.map(String));
                        valueSelect.replaceChildren();

                        (current ? current.values : []).forEach(item => {
                            addOption(valueSelect, item.id, item.name, selectedSet.has(String(item.id)));
                        });

                        if (window.jQuery) {
                            window.jQuery(valueSelect).trigger('change');
                        }
                    }

                    attrSelect.addEventListener('change', () => renderValues([]));
                    row.append(attrSelect, valuesWrap, remove);
                    container.appendChild(row);
                    attrSelect.value = taxonomy;
                    renderValues(selectedIds);

                    if (window.jQuery && window.jQuery.fn.selectWoo) {
                        window.jQuery(valueSelect).selectWoo({
                            width: '100%',
                            placeholder: 'Выберите значения',
                            closeOnSelect: false
                        });
                    }
                }

                // Старый формат хранения: одна строка на каждую пару атрибут/значение.
                // Для интерфейса объединяем строки одного атрибута в мультиселект.
                const grouped = new Map();

                if (Array.isArray(saved)) {
                    saved.forEach(item => {
                        if (!item || !item.taxonomy || !item.term_id) return;
                        if (!grouped.has(item.taxonomy)) grouped.set(item.taxonomy, []);
                        grouped.get(item.taxonomy).push(String(item.term_id));
                    });
                }

                grouped.forEach((ids, taxonomy) => addRow(taxonomy, ids));
                addButton.addEventListener('click', () => addRow());
            });
            </script>
        </td>
    </tr>
    <?php
}

add_action( 'edited_product_cat', 'plnt_category_attrs_save' );

function plnt_category_attrs_save( int $term_id ): void {
    if ( ! current_user_can( 'manage_woocommerce' ) || ! plnt_category_attrs_is_plant( $term_id ) ) {
        return;
    }

    if ( ! isset( $_POST['plnt_category_attrs_nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['plnt_category_attrs_nonce'] ) ), 'plnt_category_attrs_save' )
        || ! isset( $_POST['plnt_category_attrs_present'] ) ) {
        return;
    }

    $taxonomies = isset( $_POST['plnt_category_attr_taxonomy'] ) && is_array( $_POST['plnt_category_attr_taxonomy'] )
        ? wp_unslash( $_POST['plnt_category_attr_taxonomy'] ) : array();
    $terms_by_index = isset( $_POST['plnt_category_attr_term_ids'] ) && is_array( $_POST['plnt_category_attr_term_ids'] )
        ? wp_unslash( $_POST['plnt_category_attr_term_ids'] ) : array();
    $allowed = plnt_category_attrs_get_attributes();
    $result  = array();

    foreach ( $taxonomies as $index => $taxonomy ) {
        $taxonomy = sanitize_text_field( (string) $taxonomy );

        if ( ! isset( $allowed[ $taxonomy ] ) ) {
            continue;
        }

        foreach ( (array) ( $terms_by_index[ $index ] ?? array() ) as $raw_id ) {
            $value_id = absint( $raw_id );
            $value = $value_id ? get_term( $value_id, $taxonomy ) : false;

            if ( ! $value || is_wp_error( $value ) ) {
                continue;
            }

            $result[ $taxonomy . ':' . $value_id ] = array(
                'taxonomy' => $taxonomy,
                'term_id'  => $value_id,
            );
        }
    }

    if ( $result ) {
        update_term_meta( $term_id, 'plnt_plant_category_attributes', array_values( $result ) );
    } else {
        delete_term_meta( $term_id, 'plnt_plant_category_attributes' );
    }
}