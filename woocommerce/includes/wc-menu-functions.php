<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Хелперы */

function plnt_get_all_published_product_cat_ids() {
	global $wpdb;

	static $cat_ids = null;

	if ( null !== $cat_ids ) {
		return $cat_ids;
	}

	$sql = "
		SELECT DISTINCT tt.term_id
		FROM {$wpdb->term_relationships} tr
		INNER JOIN {$wpdb->posts} p
			ON p.ID = tr.object_id
		INNER JOIN {$wpdb->term_taxonomy} tt
			ON tt.term_taxonomy_id = tr.term_taxonomy_id
		WHERE tt.taxonomy = 'product_cat'
		AND p.post_type = 'product'
		AND p.post_status = 'publish'
	";

	$cat_ids = [];

	foreach ( $wpdb->get_col( $sql ) as $term_id ) {
		$cat_ids[ (int) $term_id ] = true;
	}

	return $cat_ids;
}

function plnt_clean_menu_name( $name, $words_to_remove = [] ) {
	if ( empty( $words_to_remove ) || ! is_array( $words_to_remove ) ) {
		return trim( $name );
	}

	return trim( str_replace( $words_to_remove, '', $name ) );
}

function plnt_get_child_terms( $term, $taxonomy = 'product_cat', $hide_empty = false ) {
  $parent_id = $term->term_id;
	$terms = get_terms( [
		'taxonomy'   => $taxonomy,
		'parent'     => (int) $parent_id,
		'hide_empty' => $hide_empty,
	] );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return [];
	}

	return $terms;
}

function plnt_get_menu_link( $args) {
  $args = wp_parse_args( $args, [
    'slug' => null,
    'taxonomy' => 'product_cat',
    'classes' => 'cats-sub-menu__item-link cats-sub-menu__item-image',
    'words_to_remove' => [],
  ]);

  if ( ! $args['slug'] ) {
		return;
	}

  $term = get_term_by( 'slug', $args['slug'], $args['taxonomy'] );
  if ( ! $term || is_wp_error( $term ) ) {
		return;
	}

  $name = plnt_clean_menu_name($term->name, $args['words_to_remove']);
  
  $term_id = $term ->term_id;  
	if ( ! $term_id ) {
		return;
	}

	$url = get_term_link( $term_id, $args['taxonomy'] );

	if ( is_wp_error( $url ) ) {
		return;
	}

	?>

	<a
		class="<?php echo esc_attr( $args['classes'] ); ?>"
		data-cat_id="<?php echo esc_attr( $term_id ); ?>"
		href="<?php echo esc_url( $url ); ?>"
	>
		<?php echo esc_html( $name ); ?>
	</a>

	<?php
}

//example, $cat_slug = 'treez-effectory', $words_to_remove - words to be removed from menu items
function get_primary_submenu($args) { 
  // $cat_slug, $show_heading = true, $words_to_remove = [], $clean_cat_name=false
  $args = wp_parse_args( $args, [
    'slug' => null,
    'taxonomy' => 'product_cat',
    'show_heading' => true,
    'words_to_remove' => [],
  ]);

  if(!$args['slug']) return;

  $term = get_term_by( 'slug', $args['slug'], $args['taxonomy'] );    
  $terms = plnt_get_child_terms($term, $args['taxonomy']); 

  if ( empty( $terms ) ) {
    return;
  }

  if ( 'product_cat' === $args['taxonomy'] ) {
    $published_cat_ids = plnt_get_all_published_product_cat_ids();

    $terms = array_filter( $terms, function( $term ) use ( $published_cat_ids ) {
      return ! empty( $published_cat_ids[ (int) $term->term_id ] );
    } );

    if ( empty( $terms ) ) {
      return;
    }
  }
  ?>
  <div class="cats-sub-menu">
    <?php 
      if($args['show_heading']) {
        $args_heading = $args;
        $args_heading['classes'] = 'cats-sub-menu__heading cats-sub-menu__item-image';
        plnt_get_menu_link($args_heading);
      }
    ?>
    <ul class="cats-sub-menu__list">
        <?php
        foreach ($terms as $term) {
          $args_link = $args;
          $args_link['slug'] = $term->slug;
          ?>
          <li class="cats-sub-menu__item">
            <?php plnt_get_menu_link($args_link); ?>
          </li>
          <?
        }
        ?>
    </ul>
  </div>
  <?php
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

	$lowest_cats = get_lowest_level_product_categories( $plants_cat_id );

	if ( empty( $lowest_cats ) ) {
		return;
	}

	$cat_ids = wp_list_pluck( $lowest_cats, 'term_id' );
	$cat_ids = array_map( 'intval', $cat_ids );

	if ( empty( $cat_ids ) ) {
		return;
	}

	$cat_ids_placeholder = implode( ',', array_fill( 0, count( $cat_ids ), '%d' ) );

	$query = "
		SELECT DISTINCT tt.term_id
		FROM {$wpdb->term_relationships} tr
		INNER JOIN {$wpdb->posts} p ON p.ID = tr.object_id
		INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
		WHERE tt.taxonomy = 'product_cat'
		AND tt.term_id IN ($cat_ids_placeholder)
		AND p.post_type = 'product'
		AND p.post_status = 'publish'
	";

	$valid_term_ids = $wpdb->get_col(
		$wpdb->prepare( $query, $cat_ids )
	);

	if ( empty( $valid_term_ids ) ) {
		return;
	}

	$valid_term_ids = array_map( 'intval', $valid_term_ids );

	$lowest_cats = array_filter( $lowest_cats, function( $cat ) use ( $valid_term_ids ) {
		return in_array( (int) $cat->term_id, $valid_term_ids, true );
	} );

	if ( empty( $lowest_cats ) ) {
		return;
	}

	$words_to_remove = [
		' (Цитрофортунелла)',
		'овая пальма',
		' (Кодиеум)',
		' (Купрессус)',
		' (Седум)',
		' (Эриокактус)',
		' (Буксус)',
		' (Крестовник)',
		' (Кентия)',
		' Одри',
	];

	$grouped_cats = [];

	foreach ( $lowest_cats as $cat ) {
		$name = plnt_clean_menu_name( $cat->name, $words_to_remove );
		$name = str_replace( 'ковое дерево', 'а', $name );
		$name = trim( $name );

		if ( ! $name ) {
			continue;
		}

		$url = get_term_link( $cat->term_id, 'product_cat' );

		if ( is_wp_error( $url ) ) {
			continue;
		}

		$letter = mb_substr( $name, 0, 1, 'UTF-8' );
		$letter = mb_strtoupper( $letter, 'UTF-8' );

		$grouped_cats[ $letter ][] = [
			'name' => $name,
			'url'  => $url,
		];
	}

	if ( empty( $grouped_cats ) ) {
		return;
	}

	ksort( $grouped_cats, SORT_LOCALE_STRING );
  echo ('<div class="cats-sub-menu cats-sub-menu--az">');
	foreach ( $grouped_cats as $letter => $cats ) {
		usort( $cats, function( $a, $b ) {
			return strcmp( $a['name'], $b['name'] );
		} );
		?>


			<div class="cats-sub-menu__wrap">
				<span class="cats-sub-menu__letter">
					<?php echo esc_html( $letter ); ?>
				</span>

				<ul class="cats-sub-menu__list">
					<?php foreach ( $cats as $cat ) : ?>
						<li class="cats-sub-menu__item-link">
							<a href="<?php echo esc_url( $cat['url'] ); ?>">
								<?php echo esc_html( $cat['name'] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		

		<?php
	}
  echo ('</div>');
}

/**
 * Выводит меню с метками WooCommerce (product_tag)
 *
 * @param string $title   Заголовок списка (первый <li>)
 * @param array  $tags    Ассоциативный массив [ 'slug' => 'Название' ]
 */
function plnt_render_product_tag_menu( $title, $tags = [] ) {
    if ( empty( $tags ) ) {
        return;
    }

    echo '<div class="cats-sub-menu">';
      echo '<span class="cats-sub-menu__heading">' . esc_html( $title ) . '</span>';
      
      echo '<ul class="cats-sub-menu__list">';
        foreach ( $tags as $slug => $label ) {
            echo '<li class="cats-sub-menu__item">';
            plnt_get_menu_link([
              'slug' => $slug,
              'taxonomy' => 'product_tag',
            ]); 
            echo '</li>';
        }
      echo '</ul>';
    echo '</div>';
}


function plnt_render_colors_attr_menu() {
	$colors = [
		'belyj'        => 'Белый',
		'chyornyj'     => 'Чёрный',
		'bezhevyy'     => 'Бежевый',
		'seryj'        => 'Серый',
		'zolotoj'      => 'Золотой',
		'serebro'      => 'Серебро',
		'terrakotovyj' => 'Терракотовый',
	];
	?>

	<ul class="cats-sub-menu__list">
		<?php foreach ( $colors as $slug => $label ) : ?>
			<li class="cats-sub-menu__item">
				<a
					class="cats-sub-menu__item-link"
					href="<?php echo esc_url( site_url( '/attribute/color/' . $slug . '/' ) ); ?>"
				>
					<?php echo esc_html( $label ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php
}

function plnt_render_treez_plants_menu() {
  global $plants_treez_cat_id;
  $args = array( 'taxonomy' => 'product_cat', 'parent' => $plants_treez_cat_id );
  $terms = get_terms( $args );
  foreach($terms as $term) {
    $words_to_remove = ['Treez','Искусственные', 'Искусственная', 'Искусственное', 'Искусственный','растения'];
    get_primary_submenu([
      'slug' => $term->slug, 
      'words_to_remove' => $words_to_remove, 
    ]);
  }
}