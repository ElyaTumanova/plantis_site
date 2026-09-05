<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$banners = get_field('banners');

if ( ! empty($banners) ) :
?>

<div class="front__hero-banners banner hero-banner-swiper swiper">
	<div class="swiper-wrapper">
	<?php
		foreach ($banners as $banner) {
			?>
			<div class="banner__inner swiper-slide">
				<div class="banner__content">

					<?php if ( ! empty($banner['title']) ) : ?>
						<span class="banner__title">
							<?php echo esc_html($banner['title']); ?>
						</span>
					<?php endif; ?>

					<?php if ( ! empty($banner['button']) ) : ?>
						<a
							class="banner__button button button--transparent"
							href="<?php echo esc_url($banner['button']['url']); ?>"
							target="<?php echo esc_attr($banner['button']['target'] ?: '_self'); ?>"
						>
							<span class="icon icon--arrow-right">
								<?php echo esc_html($banner['button']['title']); ?>
							</span>
						</a>
					<?php endif; ?>

					<?php if ( ! empty($banner['caption']) ) : ?>
						<span class="banner__caption">
							<?php echo esc_html($banner['caption']); ?>
						</span>
					<?php endif; ?>

				</div>

				<div class="banner__bg">
					<img
						class="banner__bg"
						src="<?php echo esc_url($banner['image']); ?>"
						alt=""
						width="1380"
						height="429"
						loading="eager"
						fetchpriority="high"
						decoding="async"
					>
				</div>

			</div>
			<?php
		}
	?>
	</div>

	<div class="swiper-pagination"></div>
	<div class="swiper-button-prev"></div>
	<div class="swiper-button-next"></div>
</div>

<?php endif; ?>