<?php
/**
 * Автоматические кросс-сейлы растений.
 * Настройки групп находятся в опции plnt_plant_cross_sell_groups.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/** Получить сохранённые группы в порядке их приоритета. */
function plnt_plant_cross_sell_groups(): array {
    $groups = get_option( 'plnt_plant_cross_sell_groups', array() );

    return is_array( $groups ) ? array_values( $groups ) : array();
}

/** Найти корневую категорию «Комнатные растения». */
function plnt_plant_cross_sell_root_category(): int {
    static $category_id = null;

    if ( null === $category_id ) {
        $term        = get_term_by( 'slug', 'komnatnye-rasteniya', 'product_cat' );
        $category_id = $term && ! is_wp_error( $term ) ? (int) $term->term_id : 0;
    }

    return $category_id;
}

/** ID назначенных терминов; для категорий добавляем всех родителей. */
function plnt_plant_cross_sell_product_terms( int $product_id, string $taxonomy ): array {
    $terms = wp_get_post_terms( $product_id, $taxonomy, array( 'fields' => 'ids' ) );

    if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
        return array();
    }

    $ids = array_map( 'intval', $terms );

    if ( 'product_cat' === $taxonomy ) {
        foreach ( $ids as $term_id ) {
            $ids = array_merge( $ids, array_map( 'intval', get_ancestors( $term_id, 'product_cat', 'taxonomy' ) ) );
        }
    }

    return array_values( array_unique( $ids ) );
}

/** Общая проверка категории для всей функциональности. */
function plnt_plant_cross_sell_is_plant( int $product_id ): bool {
    $root_id = plnt_plant_cross_sell_root_category();

    return $root_id > 0
        && 'product' === get_post_type( $product_id )
        && in_array( $root_id, plnt_plant_cross_sell_product_terms( $product_id, 'product_cat' ), true );
}

/**
 * Найти первую подходящую группу. Категории и метки внутри группы — условие ИЛИ.
 *
 * @return string|null Постоянный идентификатор группы.
 */
function plnt_plant_cross_sell_product_group_id( int $product_id ): ?string {
    if ( ! plnt_plant_cross_sell_is_plant( $product_id ) ) {
        return null;
    }

    $categories = plnt_plant_cross_sell_product_terms( $product_id, 'product_cat' );
    $tags       = plnt_plant_cross_sell_product_terms( $product_id, 'product_tag' );

    foreach ( plnt_plant_cross_sell_groups() as $group ) {
        $group_categories = array_map( 'absint', (array) ( $group['categories'] ?? array() ) );
        $group_tags       = array_map( 'absint', (array) ( $group['tags'] ?? array() ) );

        if ( array_intersect( $categories, $group_categories ) || array_intersect( $tags, $group_tags ) ) {
            return (string) $group['id'];
        }
    }

    return null;
}

/** Найти конфигурацию группы по её постоянному ID. */
function plnt_plant_cross_sell_get_group( string $group_id ): ?array {
    foreach ( plnt_plant_cross_sell_groups() as $group ) {
        if ( (string) ( $group['id'] ?? '' ) === $group_id ) {
            return $group;
        }
    }

    return null;
}

/**
 * Получить участников группы: publish + private, независимо от остатков.
 * Предки категорий учитываются; если товар подходит нескольким группам,
 * он возвращается только для первой группы.
 */
function plnt_plant_cross_sell_group_product_ids( string $group_id ): array {
    $group   = plnt_plant_cross_sell_get_group( $group_id );
    $root_id = plnt_plant_cross_sell_root_category();

    if ( ! $group || ! $root_id ) {
        return array();
    }

    $categories = array_values( array_filter( array_map( 'absint', (array) ( $group['categories'] ?? array() ) ) ) );
    $tags       = array_values( array_filter( array_map( 'absint', (array) ( $group['tags'] ?? array() ) ) ) );

    if ( ! $categories && ! $tags ) {
        return array();
    }

    $matching_terms = array( 'relation' => 'OR' );

    if ( $categories ) {
        $matching_terms[] = array(
            'taxonomy'         => 'product_cat',
            'field'            => 'term_id',
            'terms'            => $categories,
            'include_children' => true,
        );
    }

    if ( $tags ) {
        $matching_terms[] = array(
            'taxonomy' => 'product_tag',
            'field'    => 'term_id',
            'terms'    => $tags,
        );
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
            'relation' => 'AND',
            array(
                'taxonomy'         => 'product_cat',
                'field'            => 'term_id',
                'terms'            => array( $root_id ),
                'include_children' => true,
            ),
            $matching_terms,
        ),
    ) );

    $ids = array();

    foreach ( $query->posts as $product_id ) {
        $product_id = (int) $product_id;

        if ( plnt_plant_cross_sell_product_group_id( $product_id ) === $group_id ) {
            $ids[] = $product_id;
        }
    }

    return $ids;
}

/** Пересчитать кросс-сейлы одного участника группы. */
function plnt_plant_cross_sell_update_group_product( int $product_id, array $group_ids ): bool {
    if ( ! function_exists( 'wc_get_product' ) ) {
        return false;
    }

    $product = wc_get_product( $product_id );

    if ( ! $product ) {
        return false;
    }

    $cross_sells = array_values( array_diff( $group_ids, array( $product_id ) ) );
    $current     = array_map( 'intval', $product->get_cross_sell_ids( 'edit' ) );

    if ( $current !== $cross_sells ) {
        $product->set_cross_sell_ids( $cross_sells );
        $product->save();
    }

    return true;
}

/** Полная пересборка конкретной группы (для кнопки в настройках). */
function plnt_plant_cross_sell_update_group( string $group_id ): int {
    $product_ids = plnt_plant_cross_sell_group_product_ids( $group_id );
    $updated     = 0;

    foreach ( $product_ids as $product_id ) {
        if ( plnt_plant_cross_sell_update_group_product( $product_id, $product_ids ) ) {
            ++$updated;
        }
    }

    return $updated;
}

/**
 * Добавление нового товара: задаём ему группу и добавляем только его ID
 * в существующие списки остальных растений.
 */
function plnt_plant_cross_sell_add_product( int $product_id ): void {
    if ( ! function_exists( 'wc_get_product' )
        || ! in_array( get_post_status( $product_id ), array( 'publish', 'private' ), true ) ) {
        return;
    }

    $group_id = plnt_plant_cross_sell_product_group_id( $product_id );

    if ( null === $group_id ) {
        return;
    }

    $other_ids = array_values( array_diff(
        plnt_plant_cross_sell_group_product_ids( $group_id ),
        array( $product_id )
    ) );

    $product = wc_get_product( $product_id );

    if ( ! $product ) {
        return;
    }

    $product->set_cross_sell_ids( $other_ids );
    $product->save();

    foreach ( $other_ids as $other_id ) {
        $other = wc_get_product( $other_id );

        if ( ! $other ) {
            continue;
        }

        $cross_sells = array_map( 'intval', $other->get_cross_sell_ids( 'edit' ) );

        if ( ! in_array( $product_id, $cross_sells, true ) ) {
            $cross_sells[] = $product_id;
            $other->set_cross_sell_ids( $cross_sells );
            $other->save();
        }
    }
}

/** Удалить только ID отправленного в корзину товара из остальных растений. */
function plnt_plant_cross_sell_remove_product( int $product_id ): void {
    $root_id = plnt_plant_cross_sell_root_category();

    if ( ! $root_id || ! function_exists( 'wc_get_product' ) ) {
        return;
    }

    $query = new WP_Query( array(
        'post_type'      => 'product',
        'post_status'    => array( 'publish', 'private' ),
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'no_found_rows'  => true,
        'tax_query'      => array(
            array(
                'taxonomy'         => 'product_cat',
                'field'            => 'term_id',
                'terms'            => array( $root_id ),
                'include_children' => true,
            ),
        ),
    ) );

    foreach ( $query->posts as $other_id ) {
        $other = wc_get_product( $other_id );

        if ( ! $other ) {
            continue;
        }

        $cross_sells = array_map( 'intval', $other->get_cross_sell_ids( 'edit' ) );

        if ( in_array( $product_id, $cross_sells, true ) ) {
            $other->set_cross_sell_ids( array_values( array_diff( $cross_sells, array( $product_id ) ) ) );
            $other->save();
        }
    }
}

/**
 * Выполняем обработку на shutdown: к этому моменту WooCommerce уже сохранил
 * категории и метки нового товара. Обычное редактирование не отслеживаем.
 */
add_action( 'transition_post_status', 'plnt_plant_cross_sell_status_transition', 10, 3 );

function plnt_plant_cross_sell_status_transition( string $new_status, string $old_status, WP_Post $post ): void {
    if ( 'product' !== $post->post_type || $new_status === $old_status ) {
        return;
    }

    $active = array( 'publish', 'private' );

    if ( in_array( $new_status, $active, true ) && ! in_array( $old_status, $active, true ) ) {
        $GLOBALS['plnt_plant_cross_sell_add_queue'][ $post->ID ] = true;
    } elseif ( 'trash' === $new_status && in_array( $old_status, $active, true ) ) {
        $GLOBALS['plnt_plant_cross_sell_remove_queue'][ $post->ID ] = true;
    }
}

add_action( 'before_delete_post', 'plnt_plant_cross_sell_before_delete', 10, 2 );

function plnt_plant_cross_sell_before_delete( int $post_id, WP_Post $post ): void {
    if ( 'product' === $post->post_type ) {
        $GLOBALS['plnt_plant_cross_sell_remove_queue'][ $post_id ] = true;
    }
}

add_action( 'shutdown', 'plnt_plant_cross_sell_process_queue', 20 );

function plnt_plant_cross_sell_process_queue(): void {
    foreach ( array_keys( $GLOBALS['plnt_plant_cross_sell_remove_queue'] ?? array() ) as $product_id ) {
        plnt_plant_cross_sell_remove_product( (int) $product_id );
    }

    foreach ( array_keys( $GLOBALS['plnt_plant_cross_sell_add_queue'] ?? array() ) as $product_id ) {
        plnt_plant_cross_sell_add_product( (int) $product_id );
    }
}
