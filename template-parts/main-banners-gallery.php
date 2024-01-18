<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 
	$banner_1 = carbon_get_theme_option('main_banner_1');
	$banner_1_name = carbon_get_theme_option('main_banner_1_name');
	$banner_2 = carbon_get_theme_option('main_banner_2');
	$banner_2_name = carbon_get_theme_option('main_banner_2_name');
	$banner_3 = carbon_get_theme_option('main_banner_3');
	$banner_3_name = carbon_get_theme_option('main_banner_3_name');
	$banner_4 = carbon_get_theme_option('main_banner_4');
	$banner_4_name = carbon_get_theme_option('main_banner_4_name');
?>

<div class="main__banners nivo-main-banner-gallery">
		<img src="<?php echo $banner_1 ?>" class="main__banner-img" alt="<?php echo $banner_1_name?>" width="900" height="280">
		<img src="<?php echo $banner_2 ?>" class="main__banner-img" style="visibility:hidden;" alt="<?php echo $banner_2_name?>" width="900" height="280">
	</div>
