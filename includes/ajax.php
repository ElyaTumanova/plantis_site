<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action('wp_ajax_search-ajax', 'plnt_search_ajax_action_callback');
add_action('wp_ajax_nopriv_search-ajax', 'plnt_search_ajax_action_callback');

function plnt_search_ajax_action_callback (){
    global $plants_treez_cat_id;
    global $peresadka_cat_id;
    if(!wp_verify_nonce($_POST['nonce'], 'search-nonce')){
        wp_die('Данные отправлены не с того адреса');
    }

    $arg = array(
        'post_type' => 'product', // если нужен поиск по постам - доавляем в массив 'post'
        'post_status' => 'publish',
        's' => $_POST['s'],
        'orderby' => 'meta_value',
	      'meta_key' => '_stock_status',
        'order' => 'ASC',
        // 'posts_per_page' => -1,
        'meta_query' => array( 
            array(
                'key'       => '_stock_status',
                'value'     => 'outofstock',
                'compare'   => 'NOT IN',
                )
                
        ),
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'operator' => 'NOT IN',
                'terms' => [$plants_treez_cat_id, $peresadka_cat_id],
                'include_children' => 1,
            )
        )
    );
    $query_ajax = new WP_Query($arg);
    $product_sku_id = wc_get_product_id_by_sku( $query_ajax->query_vars[ 's' ] );
    $json_data['out'] = ob_start(PHP_OUTPUT_HANDLER_CLEANABLE);
    if ($query_ajax->have_posts() || $product_sku_id) {
        // echo '<pre>';
        // print_r( $query_ajax );
        // echo '</pre>';
        while ($query_ajax->have_posts()){
            $query_ajax->the_post();
            $product = wc_get_product( get_the_ID() );
            // print_r($product);
            $short_descr = $product->get_short_description();
            $sale = get_post_meta( get_the_ID(), '_sale_price', true);
            ?>
            <div class="search-result__item">
                <a href="<?php echo get_permalink();?>" class="search-result__link" target="blank">
                    <?php plnt_check_stock_status();?>
                    <img src="<?php echo get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );?>" class="search-result__image" alt="<?php echo get_the_title();?>">
                    <div class="search-result__info">
                        <span class="search-result__title"><?php echo get_the_title();?></span>
                        <span class="search-result__descr"><?php echo $short_descr?></span>
                        <?php if ($sale) {
                            ?>
                            <span class="search-result__reg-price"><?php echo get_post_meta( get_the_ID(), '_regular_price', true);?>&#8381;</span>
                            <span class="search-result__price"><?php echo get_post_meta( get_the_ID(), '_sale_price', true);?>&#8381;</span>
                            <?php
                        } else {
                            ?>
                            <span class="search-result__price"><?php echo get_post_meta( get_the_ID(), '_price', true);?>&#8381;</span>
                            <?php 
                        }
                        ?>
                    </div>
                </a>  
            </div>
            <?php
        }
        if ($product_sku_id) {
            $product = wc_get_product( $product_sku_id );
            //print_r($product);
            $short_descr = $product->get_short_description();
            $title = $product->get_title();
            $sale = get_post_meta( $product_sku_id, '_sale_price', true);
            ?>
            <div class="search-result__item">
                <a href="<?php echo $product->get_permalink();?>" class="search-result__link" target="blank">
                    <img src="<?php echo get_the_post_thumbnail_url( $product_sku_id, 'thumbnail' );?>" class="search-result__image" alt="<?php echo $title;?>">
                    <div class="search-result__info">
                        <span class="search-result__title"><?php echo $title?></span>
                        <span class="search-result__descr"><?php echo $short_descr?></span>
                        <?php if ($sale) {
                            ?>
                            <span class="search-result__reg-price"><?php echo get_post_meta( $product_sku_id, '_regular_price', true);?>&#8381;</span>
                            <span class="search-result__price"><?php echo get_post_meta( $product_sku_id, '_sale_price', true);?>&#8381;</span>
                            <?php
                        } else {
                            ?>
                            <span class="search-result__price"><?php echo get_post_meta( $product_sku_id, '_price', true);?>&#8381;</span>
                            <?php 
                        }
                        ?>
                    </div>
                </a>  
            </div>
            <?php
        }
        ?>
        <input class="search-result__btn button" type="submit" form ="searchform" value="Посмотреть все" />
        <?php
    } else {
        ?>
        <div class="search-result__text">Ничего не найдено</div>
        <?php
    }
    $json_data['out'] = ob_get_clean();
    wp_send_json($json_data);
    wp_die();
}