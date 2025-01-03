<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 

	$banner_1 = carbon_get_theme_option('main_banner_1_mob');
	$banner_2 = carbon_get_theme_option('main_banner_2_mob');
	$banner_3 = carbon_get_theme_option('main_banner_3_mob');
	$banner_4 = carbon_get_theme_option('main_banner_4_mob');

	$banner_1_mob = carbon_get_theme_option('main_banner_1');
	$banner_2_mob = carbon_get_theme_option('main_banner_2');
	$banner_3_mob = carbon_get_theme_option('main_banner_3');
	$banner_4_mob = carbon_get_theme_option('main_banner_4');

	$banner_1_name = carbon_get_theme_option('main_banner_1_name');
	$banner_2_name = carbon_get_theme_option('main_banner_2_name');
	$banner_3_name = carbon_get_theme_option('main_banner_3_name');
	$banner_4_name = carbon_get_theme_option('main_banner_4_name');

	$banners_arr = carbon_get_theme_option('banners');

	print_r($banners_arr[0]['banner_desktop']);
?>

<div class="main__banners main__banners-swiper swiper">
	<div class="swiper-wrapper">
		<a class="swiper-slide" href="https://plantis.shop/product-category/product_of_week/"><img src="<?php echo $banner_3 ?>" class="main__banner-img" alt="<?php echo $banner_3_name?>" loading=" lazy"></a>
		<img class="swiper-slide" src="<?php echo $banner_2 ?>" class="main__banner-img" alt="<?php echo $banner_2_name?>" loading=" lazy">
	</div>
	<div class="swiper-pagination"></div>
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
</div>

<div class="main__banners_mob main__banners-swiper swiper">
	<div class="swiper-wrapper">
		<a class="swiper-slide" href="https://plantis.shop/product-category/product_of_week/"> <img src="<?php echo $banner_3_mob ?>" class="main__banner-img_mob" alt="<?php echo $banner_3_name?>" loading=" lazy"></a>
		<img class="swiper-slide" src="<?php echo $banner_2_mob ?>" class="main__banner-img_mob" alt="<?php echo $banner_2_name?>" loading=" lazy">
	</div>
	<div class="swiper-pagination"></div>
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
</div>