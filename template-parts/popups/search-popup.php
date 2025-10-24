<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$close_icon = carbon_get_theme_option('close_icon')
?>

<div class="search-popup popup">
    <div class="search-popup-wrap">
        <div class="search__wrap container">
            <?//get_search_form();?>
            <div class="search__close"><?php echo $close_icon ?></div>
        </div>
        <div class="search-result container"></div>
    </div>
    <!-- <div class="search__popup-overlay popup-overlay"></div> -->
</div>