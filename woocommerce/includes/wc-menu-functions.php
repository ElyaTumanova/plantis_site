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

    $category_thumbnail = get_term_meta(137, 'thumbnail_id', true);
    $image = wp_get_attachment_url($category_thumbnail);
    ?>
    
    <a 
        class ="header__main-submenu-item_accent header__main-submenu-item_link header__main-submenu-item_image" 
        data-cat_id = <?php echo $term_id?> 
        href="<?php echo site_url().$link_base. $cat_slug.'/'?>">
        <?php echo $cat_name?>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path d="M7 7h8.586L5.293 17.293l1.414 1.414L17 8.414V17h2V5H7v2z"/></svg>
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
                href="<?php echo $link?>" 
                target='_blank'>
                <?php echo $name?>
            </a>
            <?
        }
        ?>
    </div> <?php
}


add_action( 'wp_ajax_get_menu_cats_image', 'plnt_get_menu_cats_image' );
add_action( 'wp_ajax_nopriv_get_menu_cats_image', 'plnt_get_menu_cats_image' );
function plnt_get_menu_cats_image() {
    $cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;

    if ( ! $cat_id ) {
        wp_send_json_error('Invalid category ID');
    }
    wp_send_json_success( [ 'test' => $cat_id ] );
    // $thumbnail_id = get_term_meta( $cat_id, 'thumbnail_id', true );
    // $thumbnail_url = wp_get_attachment_url( $thumbnail_id );

    // if ( $thumbnail_url ) {
    //     wp_send_json_success( [ 'image_url' => esc_url( $thumbnail_url ) ] );
    // } else {
    //     wp_send_json_error('Image not found');
    // }
}