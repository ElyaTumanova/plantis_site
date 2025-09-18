<?php get_header(); 

?>

<div class="gift-card content-area">
  <h1>Hi this is gift card page</h1>
    <?php 
    $gcnum = get_query_var('gcnum');
    $gift_card = mytheme_get_giftcard_by_code( $gcnum );

    $query = new WP_Query( array(
        'post_type'      => 'gift_card',     // тип поста для YITH карт
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'meta_query'     => array(
            array(
                'key'   => 'post_title',   // метаполе с номером карты
                'value' => $gcnum,
            ),
        ),
        'fields' => 'ids',
    ) );

    if ( $gift_card ) {
        echo 'Номер карты: ' . esc_html( $gift_card->gift_card_number );
        echo '<br>Баланс: ' . wc_price( $gift_card->get_balance() );
        echo '<br>Действует до: ' . date_i18n( get_option( 'date_format' ), $gift_card->get_expiration() );
    } else {
        echo 'Карта с таким номером не найдена.';
    }

    echo('<pre>');
    print_r($gcnum);
    print_r($query);
    echo('</pre>');
    ?>
</div>




<?php 

?>

<?php get_footer();?>