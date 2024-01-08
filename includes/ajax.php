<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action('wp_ajax_search-ajax', 'plnt_search_ajax_action_callback');
add_action('wp_ajax_nopriv_search-ajax', 'plnt_search_ajax_action_callback');

function plnt_search_ajax_action_callback (){
    if(!wp_verify_nonce($_POST['nonce'], 'search_nonce')){
        echo "<script>console.log('{$_POST}');</script>";
        wp_die('Данные отправлены не с того адреса');
    }

    $arg = array(
        'post_type' => array('product'), // если нужен поиск по постам - доавляем в массив 'post'
        'post_status' => 'publish',
        's' => $_POST['s']
    );
    $query_ajax = new WP_Query($arg);
    $json_data['out'] = ob_start(PHP_OUTPUT_HANDLER_CLEANABLE);
    if ($query_ajax->have_posts()) {
        while ($query_ajax->have_posts()){
            $query_ajax->the_post();
            ?>
            <div class="search__title"><?php echo get_the_title();?></div>
            <?php
        }
    }
    $json_data['out'] = ob_get_clean();
    wp_send_json($json_data);
    wp_die();
}