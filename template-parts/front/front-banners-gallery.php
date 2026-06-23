<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php 

$banners_arr = carbon_get_theme_option('banners');
	
?>
<div class="front__hero-banners banner hero-banner-swiper swiper">
	<div class="swiper-wrapper">
	<?php
		foreach ($banners_arr as $banner) {
			?>
      <div class="banner__inner swiper-slide">
        <div class="banner__content">
          <span class="banner__title">Бесплатно пересадим при покупке горшка*</span>
          <?php if($banner['banner_link']):?>
            <a class="banner__button button button--transparent"
            href="<?php echo $banner['banner_link'] ?>"
            >
            <span class="icon icon--arrow-right">Купить</span>
          </a>
          <?endif;?>
          <span class="banner__caption">*Кроме кашпо Trezz и Lechuza диаметром больше 26 см</span>
        </div>
        <div class="banner__bg" style="background-image:url(<?php echo $banner['banner_desktop'] ?>)"></div>
      </div>
			<?php 
		}
	?>
	</div>
	<div class="swiper-pagination"></div>
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
</div>

<div class="front__hero-banners_mob hero-banner-swiper swiper d-none">
	<div class="swiper-wrapper">
	<?php
		foreach ($banners_arr as $banner) {
			?>
			<a class="swiper-slide <?php if(!$banner['banner_link']) : ?> front__hero-banners_no-link <?php endif; ?>" href="<?php echo $banner['banner_link'] ?>"><img src="<?php echo $banner['banner_mob'] ?>" class="main__banner-img" alt="<?php echo $banner['banner_name']?>" loading=" lazy"></a>
			<?php 
		}
	?>
	</div>
	<div class="swiper-pagination"></div>
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
</div>