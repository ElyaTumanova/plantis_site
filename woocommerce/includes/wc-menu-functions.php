<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//example, $cat_slug = 'treez-effectory', $link_base = '/product-category/kashpo-treez/', $words_to_remove - words to be removed from menu items
function get_primary_submenu($cat_slug,$link_base,$words_to_remove = [], $clean_cat_name=false) { 
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
        href="<?php echo site_url().$link_base. $cat_slug.'/'?>">
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
            $link = site_url().$link_base. $cat_slug.'/'.$term->slug;
            ?>
            <a 
                class="header__main-submenu-item_image" 
                data-cat_id = <?php echo $term->term_id?> 
                href="<?php echo $link?>">
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
            href="<?php echo site_url().$link_base.'/'. $cat_slug.'/'?>">
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
                        $link = site_url().$link_base.'/'. $cat_slug.'/'.$term->slug;
                        ?>
                        <li class="catalog__dropdown catalog__node catalog__node_lvl_2">
                            <a 
                                href="<?php echo $link?>">
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
                                            $link_sub = site_url().$link_base. '/'.$cat_slug.'/'.$term->slug.'/'.$term_sub->slug;
                                            ?>
                                            <li class="catalog__node catalog__node_lvl_3">
                                                <a 
                                                    href="<?php echo $link_sub?>">
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
    global $plants_cat_id;
    $lowest_cats = get_lowest_level_product_categories($plants_cat_id); // начиная с корня
    function sortByName($a, $b) {
        return strcmp($a->name, $b->name);
    }
    usort($lowest_cats, "sortByName");
    foreach ( $lowest_cats as $cat ) {
        print_r($cat);
        //echo $cat->name . ' (ID: ' . $cat->term_id . ')<br>';
        // $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
        // $thumbnail_url = wp_get_attachment_url( $thumbnail_id );
        // echo($thumbnail_url);
        ?>
        <li class="header__main-submenu-item">
            <a href="<?php echo site_url()?>/usluga-peresadki-komnatnyh-rastenij/"><?php echo $cat->name?></a>
        </li> 
        <?php
    }
}