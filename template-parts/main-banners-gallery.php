<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 

$banners_arr = carbon_get_theme_option('banners');
	
?>
<div class="main__banners main__banners-swiper swiper">
	<div class="swiper-wrapper">
	<?php
		foreach ($banners_arr as $banner) {
			?>
			<a class="swiper-slide <?php if(!$banner['banner_link']) : ?> main__banners_no-link <?php endif; ?>" href="<?php echo $banner['banner_link'] ?>"><img src="<?php echo $banner['banner_desktop'] ?>" class="main__banner-img" alt="<?php echo $banner['banner_name']?>" loading=" lazy"></a>
			<?php 
		}
	?>
	</div>
	<div class="swiper-pagination"></div>
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
</div>

<div class="main__banners_mob main__banners-swiper swiper">
	<div class="swiper-wrapper">
	<?php
		foreach ($banners_arr as $banner) {
			?>
			<a class="swiper-slide <?php if(!$banner['banner_link']) : ?> main__banners_no-link <?php endif; ?>" href="<?php echo $banner['banner_link'] ?>"><img src="<?php echo $banner['banner_mob'] ?>" class="main__banner-img" alt="<?php echo $banner['banner_name']?>" loading=" lazy"></a>
			<?php 
		}
	?>
	</div>
	<div class="swiper-pagination"></div>
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
</div>