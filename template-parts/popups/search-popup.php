<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="search-popup">
    <div class="search">
        <div class="search__wrap container">
            <?get_search_form();?>
            <div class="search__close">&#10006;</div>
        </div>
        <div class="search-result"></div>
    </div>
    <!-- <div class="popup-overlay"></div> -->
</div>	