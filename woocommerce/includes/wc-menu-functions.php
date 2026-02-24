<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//example, $cat_slug = 'treez-effectory', $words_to_remove - words to be removed from menu items
function get_primary_submenu($cat_slug,$words_to_remove = [], $clean_cat_name=false) { 
    $term = get_term_by( 'slug', $cat_slug, 'product_cat' );
    $term_id = $term->term_id;
    $args = array( 'taxonomy' => 'product_cat', 'parent' => $term_id );  
    $terms = get_terms( $args ); 
    $cat_name = $term->name;

    if($words_to_remove && $clean_cat_name) {
        foreach ($words_to_remove as $word) {
            $cat_name = str_replace($word,'',$cat_name);
        }
    }
    ?>
    
    <a 
        class ="header__main-submenu-item_accent header__main-submenu-item_arrow header__main-submenu-item_image" 
        data-cat_id = <?php echo $term_id?> 
        href="<?php echo get_term_link($term_id,'product_cat')?>">
        <?php echo $cat_name?>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path d="M7 17L17 7M7 7h10v10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
    <div class="header__main-submenu-item_row">
        <?php
        foreach ($terms as $term) {
            $name = $term ->name;
            if($words_to_remove) {
                foreach ($words_to_remove as $word) {
                    $name = str_replace($word,'',$name);
                }
            }
            ?>
            <a 
                class="header__main-submenu-item_image" 
                data-cat_id = <?php echo $term->term_id?> 
                href="<?php echo get_term_link($term->term_id,'product_cat')?>">
                <?php echo $name?>
            </a>
            <?
        }
        ?>
    </div> <?php
}

function get_catalog_submenu($cat_slug,$link_base,$levels=1, $cats_exclude=[], $words_to_remove=[], $clean_cat_name=false) { 
    $term = get_term_by( 'slug', $cat_slug, 'product_cat' );
    $term_id = $term->term_id;
    $args = array( 'taxonomy' => 'product_cat', 'parent' => $term_id );  
    $terms = get_terms( $args ); 
    $cat_name = $term->name;

    if($levels >= 1):?>
    <li class="catalog__dropdown catalog__node catalog__node_lvl_1">
        <a
            href="<?php echo get_term_link($term_id,'product_cat')?>">
            <?php echo $cat_name?>
        </a>
        <?php if($levels >= 2):?>
        <span class="menu__dropdown-arrow">next</span>
        <ul class = "sub-menu catalog__dropdown-menu catalog__dropdown-menu_lvl_2">
            <?php
            
            if($terms):
                foreach ($terms as $term) {
                    if(!in_array($term->term_id,$cats_exclude )):
                        $name = $term ->name;
                        if($words_to_remove && $clean_cat_name) {
                            foreach ($words_to_remove as $word) {
                                $name = str_replace($word,'',$name);
                            }
                        }
                        ?>
                        <li class="catalog__dropdown catalog__node catalog__node_lvl_2">
                            <a 
                                href="<?php echo get_term_link($term->term_id,'product_cat')?>">
                                <?php echo $name?>
                            </a>
                            <?php if($levels >= 3):?>
                            <span class="menu__dropdown-arrow">next</span>
                            <ul class = "sub-menu catalog__dropdown-menu catalog__dropdown-menu_lvl_3">
                                <?php 
                                    $term_sub = get_term_by( 'slug', $term->slug, 'product_cat' );
                                    $term_sub_id = $term_sub->term_id;
                                    $args_sub = array( 'taxonomy' => 'product_cat', 'parent' => $term_sub_id );  
                                    $terms_sub = get_terms( $args_sub ); 
                                    if($terms_sub):
                                        foreach ($terms_sub as $term_sub) {
                                            $name_sub = $term_sub ->name;
                                            if($words_to_remove) {
                                                foreach ($words_to_remove as $word) {
                                                    $name_sub = str_replace($word,'',$name_sub);
                                                }
                                            }
                                            ?>
                                            <li class="catalog__node catalog__node_lvl_3">
                                                <a 
                                                    href="<?php echo get_term_link($term_sub->term_id,'product_cat')?>">
                                                    <?php echo $name_sub?>
                                                </a>
                                            </li>
                                            <?php 
                                        }
                                    endif;    
                                ?>                  
                            </ul>
                            <?php endif;?>
                        </li>
                        <?
                    endif;
                }
            endif;
            ?>
        </ul>
        <?php endif;?>
    </li>
    <?php endif;?>
  <?php
}


add_action( 'wp_ajax_get_menu_cats_image', 'plnt_get_menu_cats_image' );
add_action( 'wp_ajax_nopriv_get_menu_cats_image', 'plnt_get_menu_cats_image' );
function plnt_get_menu_cats_image() {
    $image_urls = [];
    $cat_id_str = isset($_POST['cat_id']) ? ($_POST['cat_id']) : '';

    if ( ! $cat_id_str ) {
        wp_send_json_error('Invalid category ID');
    }

    $cat_ids = explode(',',$cat_id_str);

    foreach($cat_ids as $id) {
        $thumbnail_id = get_term_meta( $id, 'thumbnail_id', true );
        $thumbnail_url = wp_get_attachment_url( $thumbnail_id );

        $image_urls['id_'.$id] = $thumbnail_url; 
    }
    if ( $image_urls ) {
        wp_send_json_success( [ 'image_url' => $image_urls ] );
    } else {
        wp_send_json_error('Image not found');
    }
}


function get_az_palnts_submenu() {
    global $plants_cat_id, $wpdb;

    $lowest_cats = get_lowest_level_product_categories($plants_cat_id);

    if (empty($lowest_cats)) {
        return;
    }

    // получаем ID наших категорий
    $cat_ids = wp_list_pluck($lowest_cats, 'term_id');
    $cat_ids_placeholder = implode(',', array_fill(0, count($cat_ids), '%d'));

    // Один SQL-запрос — получаем категории, где есть publish товары
    $query = "
        SELECT DISTINCT tr.term_taxonomy_id
        FROM {$wpdb->term_relationships} tr
        INNER JOIN {$wpdb->posts} p ON p.ID = tr.object_id
        INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
        WHERE tt.taxonomy = 'product_cat'
        AND tt.term_id IN ($cat_ids_placeholder)
        AND p.post_type = 'product'
        AND p.post_status = 'publish'
    ";

    $prepared = $wpdb->prepare($query, $cat_ids);
    $valid_term_taxonomy_ids = $wpdb->get_col($prepared);

    if (empty($valid_term_taxonomy_ids)) {
        return;
    }

    // получаем term_id по найденным term_taxonomy_id
    $valid_term_ids = $wpdb->get_col("
        SELECT term_id 
        FROM {$wpdb->term_taxonomy}
        WHERE term_taxonomy_id IN (" . implode(',', array_map('intval', $valid_term_taxonomy_ids)) . ")
    ");

    // фильтрация массива
    $lowest_cats = array_filter($lowest_cats, function($cat) use ($valid_term_ids) {
        return in_array($cat->term_id, $valid_term_ids);
    });

    // сортировка
    usort($lowest_cats, function($a, $b) {
        return strcmp($a->name, $b->name);
    });

    $words_to_remove = [' (Цитрофортунелла)','овая пальма', ' (Кодиеум)', ' (Купрессус)', ' (Седум)', ' (Эриокактус)', ' (Буксус)', ' (Крестовник)', ' (Кентия)', ' Одри'];

    foreach ($lowest_cats as $cat) {
        $name = $cat->name;

        foreach ($words_to_remove as $word) {
            $name = str_replace($word, '', $name);
            $name = str_replace('ковое дерево', 'а', $name);
        }
        ?>
        <li class="header__main-submenu-item">
            <a href="<?php echo esc_url(get_term_link($cat->term_id,'product_cat')); ?>">
                <?php echo esc_html($name); ?>
            </a>
        </li>
        <?php
    }
}



/**
 * Выводит меню с метками WooCommerce (product_tag)
 *
 * @param string $title   Заголовок списка (первый <li>)
 * @param array  $tags    Ассоциативный массив [ 'slug' => 'Название' ]
 */
  function render_product_tag_menu( $title, $tags = [] ) {
      if ( empty( $tags ) ) {
          return;
      }

      echo '<ul class="header__main-submenu_lvl1">';
      echo '<li class="header__main-submenu-item header__main-submenu-item_accent">' . esc_html( $title ) . '</li>';

      foreach ( $tags as $slug => $label ) {
          $tag = get_term_by( 'slug', $slug, 'product_tag' );

          if ( $tag && ! is_wp_error( $tag ) ) {
              $url     = get_term_link( $tag, 'product_tag' );
              $data_id = (int) $tag->term_id; // для меток используем term_id
          } else {
              // fallback если метка не найдена
              $url     = trailingslashit( site_url( '/product-tag/' . $slug ) );
              $data_id = '';
          }

          echo '<li class="header__main-submenu-item">';
          echo '<a class="header__main-submenu-item_image" data-cat_id="' . esc_attr( $data_id ) . '" href="' . esc_url( $url ) . '">';
          echo esc_html( $label );
          echo '</a>';
          echo '</li>';
      }

      echo '</ul>';
}
