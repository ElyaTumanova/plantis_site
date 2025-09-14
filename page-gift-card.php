<?php get_header(); 

?>

<div class="gift-card content-area">
  <h1>Hi this is gift card page</h1>
    <?php 
    $gcnum = get_query_var('gcnum');
    $gift_card = YWGC_Gift_Card_Premium::get_gift_card_by_code( $gcnum );

    echo('<pre>');
    print_r($gcnum);
    print_r($gift_card);
    echo('</pre>');
    ?>
</div>




<?php 

?>

<?php get_footer();?>