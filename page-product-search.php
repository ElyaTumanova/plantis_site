<?php 
/**
 * Template Name: Product Search (Custom)
 */

get_header(); 
$s = get_query_var('s');
// $s = get_search_query(); // строка поиска из ?s=
$paged = max(1, (int) get_query_var('paged'));
$per_page = 24;

echo($s);
?>



<?php get_footer(); ?>