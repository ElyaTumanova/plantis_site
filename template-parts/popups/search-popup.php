<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="search-popup popup">
    <div class="search">
        <div class="search__wrap container">
            <?//get_search_form();?>
            <?php aws_get_search_form( true ); ?>
            <div class="search__close">&#10006;</div>
        </div>
        <div class="search-result container"></div>
    </div>
    <div class="search__popup-overlay popup-overlay"></div>
</div>