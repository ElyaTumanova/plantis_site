<?php get_header(); 

?>

<div class="gift-card content-area">
  <h1>Hi this is gift card page</h1>
    <?php 
    $gcnum = get_query_var('gcnum');
    echo('<pre>');
    print_r($gcnum);
    echo('</pre>');
    ?>
</div>




<?php 

?>

<?php get_footer();?>