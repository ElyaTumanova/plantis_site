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
	<?php foreach ($banners_arr as $banner): ?>
		<a 
			class="swiper-slide <?php if (!$banner['banner_link']) echo 'main__banners_no-link'; ?>" 
			<?php if ($banner['banner_link']): ?> href="<?php echo $banner['banner_link']; ?>" <?php endif; ?>
		>
			<img
				src="<?php echo $banner['banner_desktop']; ?>"
				srcset="<?php echo $banner['banner_mob']; ?> 767w, <?php echo $banner['banner_desktop']; ?> 1200w"
				sizes="(max-width: 767px) 100vw, 1200px"
				class="main__banner-img"
				alt="<?php echo htmlspecialchars($banner['banner_name']); ?>"
				loading="lazy"
			>
		</a>
	<?php endforeach; ?>
	</div>

	<div class="swiper-pagination"></div>
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
</div>
