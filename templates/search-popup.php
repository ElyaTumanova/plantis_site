<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function plnt_search_popup() {
	?>
        <div class="search">
            <?get_search_form();?>
            <div class="search-result"></div>
        </div><!-- .search -->
	<?php
}

