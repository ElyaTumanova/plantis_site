<?php
/**
 * Апсейлы комнатных растений: горшки по атрибуту «Для пересадки».
 * Пересчёт только по кнопкам в карточке товара и настройках групп.
 * Группы хранятся в опции plnt_plant_upsell_groups.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Найти таксономию атрибута, в том числе когда его slug кириллический. */
function plnt_plant_upsell_attribute_taxonomy(): string {
    if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
        return '';
    }

    foreach ( (array) wc_get_attribute_taxonomies() as $attribute ) {
        $slug  = strtolower( (string) ( $attribute->attribute_name ?? '' ) );
        $label = trim( (string) ( $attribute->attribute_label ?? '' ) );

        if ( ! in_array( $slug, array( 'для-пересадки', 'dlya-peresadki' ), true )
            && ! in_array( $label, array( 'Для пересадки', 'для пересадки' ), true ) ) {
            continue;
        }

        $taxonomy = wc_attribute_taxonomy_name( (string) $attribute->attribute_name );

        if ( taxonomy_exists( $taxonomy ) ) {
            return $taxonomy;
        }
    }

    return '';
}

/** Список соответствий: термин атрибута => набор ID горшков. */
function plnt_plant_upsell_groups(): array {
    $groups = get_option( 'plnt_plant_upsell_groups', array() );

    return is_array( $groups ) ? array_values( $groups ) : array();
}

/** Корневые категории горшков; при проверке товара учитываются также потомки. */
function plnt_plant_upsell_pot_category_ids(): array {
    static $ids = null;

    if ( null === $ids ) {
        $ids = array();

        foreach ( array( 'gorshki_i_kashpo', 'kashpo-treez', 'kashpo-lechuza' ) as $slug ) {
            $term = get_term_by( 'slug', $slug, 'product_cat' );

            if ( $term && ! is_wp_error( $term ) ) {
                $ids[] = (int) $term->term_id;
            }
        }
    }

    return $ids;
}

/** Выбирать можно только существующие товары из разрешённых категорий горшков. */
function plnt_plant_upsell_is_allowed_pot( int $product_id ): bool {
    static $result = array();

    if ( isset( $result[ $product_id ] ) ) {
        return $result[ $product_id ];
    }

    $categories = plnt_plant_upsell_pot_category_ids();

    $result[ $product_id ] = $product_id > 0
        && (bool) $categories
        && 'product' === get_post_type( $product_id )
        && in_array( get_post_status( $product_id ), array( 'publish', 'private' ), true )
        && (bool) array_intersect(
            $categories,
            plnt_plant_cross_sell_product_terms( $product_id, 'product_cat' )
        );

    return $result[ $product_id ];
}

/** Все горшки из групп, соответствующих значениям атрибута растения (объединение). */
function plnt_plant_upsell_target_ids( int $product_id ): array {
    $taxonomy = plnt_plant_upsell_attribute_taxonomy();

    if ( ! $taxonomy || ! plnt_plant_cross_sell_is_plant( $product_id ) ) {
        return array();
    }

    $term_ids = wp_get_post_terms( $product_id, $taxonomy, array( 'fields' => 'ids' ) );

    if ( is_wp_error( $term_ids ) || ! $term_ids ) {
        return array();
    }

    $term_ids = array_map( 'intval', $term_ids );
    $pot_ids  = array();

    foreach ( plnt_plant_upsell_groups() as $group ) {
        if ( ! in_array( (int) ( $group['term_id'] ?? 0 ), $term_ids, true ) ) {
            continue;
        }

        foreach ( (array) ( $group['products'] ?? array() ) as $pot_id ) {
            $pot_id = absint( $pot_id );

            if ( $pot_id !== $product_id && plnt_plant_upsell_is_allowed_pot( $pot_id ) ) {
                $pot_ids[ $pot_id ] = $pot_id;
            }
        }
    }

    return array_values( $pot_ids );
}

/** Обновить только стандартное поле апсейлов у комнатного растения. */
function plnt_plant_upsell_update_product( int $product_id ): bool {
    if ( ! function_exists( 'wc_get_product' )
        || ! in_array( get_post_status( $product_id ), array( 'publish', 'private' ), true )
        || ! plnt_plant_cross_sell_is_plant( $product_id ) ) {
        return false;
    }

    $product = wc_get_product( $product_id );

    if ( ! $product ) {
        return false;
    }

    $target_ids  = plnt_plant_upsell_target_ids( $product_id );
    $current_ids = array_map( 'intval', $product->get_upsell_ids( 'edit' ) );

    if ( $current_ids !== $target_ids ) {
        $product->set_upsell_ids( $target_ids );
        $product->save();
    }

    return true;
}

/** Получить всех действующих комнатных растений для пакетного обновления. */
function plnt_plant_upsell_all_plant_ids(): array {
    $root_id = plnt_plant_cross_sell_root_category();

    if ( ! $root_id ) {
        return array();
    }

    $query = new WP_Query( array(
        'post_type'              => 'product',
        'post_status'            => array( 'publish', 'private' ),
        'posts_per_page'         => -1,
        'fields'                 => 'ids',
        'no_found_rows'         => true,
        'orderby'               => 'ID',
        'order'                 => 'ASC',
        'update_post_meta_cache' => false,
        'tax_query'             => array(
            array(
                'taxonomy'         => 'product_cat',
                'field'            => 'term_id',
                'terms'            => array( $root_id ),
                'include_children' => true,
            ),
        ),
    ) );

    return array_map( 'intval', $query->posts );
}

// Автоматических хуков сохранения нет: апсейлы обновляются только вручную
// в карточке товара или отдельной кнопкой массового пересчёта.
