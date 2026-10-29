<?php
// не используется
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="search-popup popup search-result-popup" data-js-search>
    <div class="search-popup-wrap">
        <div class="search__wrap">
            <div class="search__input-mob search">
              <?php plnt_search_form( 'searchform-mobile' ); ?>
              <?php //get_search_form();?>
              <div class="search__icon search__icon--search"><?php echo plnt_icon('search') ?></div>
              <div class="search__icon search__icon--close"><?php echo plnt_icon('close') ?></div>
            </div>

            <div class="search__close popup__close"><?php echo plnt_icon('close') ?></div>
        </div>
        <div class="search-result search-result--mob"></div>
    </div>
    <div class="search__popup-overlay popup-overlay"></div>
</div>