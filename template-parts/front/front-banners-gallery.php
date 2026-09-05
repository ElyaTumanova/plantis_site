<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$banners = get_field('banners');

if ( ! empty($banners) ) :
?>
	<div class="front__hero-banners banner hero-banner-swiper swiper">
		<div class="swiper-wrapper">

			<?php foreach ($banners as $banner) :

				$image   = $banner['image'] ?? '';
				$title   = $banner['title'] ?? '';
				$caption = $banner['caption'] ?? '';
				$button  = $banner['button'] ?? [];

				$button_url    = $button['url'] ?? '';
				$button_title  = $button['title'] ?? '';
				$button_target = $button['target'] ?? '_self';
			?>
				<div class="banner__inner swiper-slide">

					<div class="banner__content">

						<?php if ($title) : ?>
							<span class="banner__title">
								<?php echo esc_html($title); ?>
							</span>
						<?php endif; ?>

						<?php if ($button_url) : ?>
							<a
								class="banner__button button button--transparent"
								href="<?php echo esc_url($button_url); ?>"
								target="<?php echo esc_attr($button_target); ?>"
							>
								<span class="icon icon--arrow-right">
									<?php echo esc_html($button_title); ?>
								</span>
							</a>
						<?php endif; ?>

						<?php if ($caption) : ?>
							<span class="banner__caption">
								<?php echo esc_html($caption); ?>
							</span>
						<?php endif; ?>

					</div>

					<?php if ($image) : ?>
						<div class="banner__bg">
							<img
								src="<?php echo esc_url($image); ?>"
								alt=""
								width="1380"
								height="429"
								loading="eager"
								fetchpriority="high"
								decoding="async"
							>
						</div>
					<?php endif; ?>

				</div>
			<?php endforeach; ?>

		</div>

		<div class="swiper-pagination"></div>
		<div class="swiper-button-prev"></div>
		<div class="swiper-button-next"></div>
	</div>
<?php endif; ?>