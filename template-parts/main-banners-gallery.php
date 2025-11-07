<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 

$banners_arr = carbon_get_theme_option('banners');

print_r($banners_arr);
	
?>
<div class="main__banners main__banners-swiper swiper">
	<div class="swiper-wrapper">
	<?php foreach ($banners_arr as $banner): ?>
		<a 
			class="swiper-slide <?php if (!$banner['banner_link']) echo 'main__banners_no-link'; ?>" 
			<?php if ($banner['banner_link']): ?> href="<?php echo $banner['banner_link']; ?>" <?php endif; ?>
		>
			<picture>
				<!-- Мобильное изображение -->
				<source 
					srcset="<?php echo $banner['banner_mob']; ?>" 
					media="(max-width: 767px)"
				>
				<!-- Десктопное изображение -->
				<img 
					src="<?php echo $banner['banner_desktop']; ?>" 
					class="main__banner-img" 
					alt="<?php echo htmlspecialchars($banner['banner_name']); ?>" 
					loading="lazy"
				>
			</picture>
		</a>
	<?php endforeach; ?>
	</div>

	<div class="swiper-pagination"></div>
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
</div>
