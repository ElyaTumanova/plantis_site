<?php

get_header(); ?>

<div class="content-area">
	<div>может быть это главная?</div>
	<main id="main" class="site-main" role="main">

	<div class="main__banners">
		<?php $banner_1 = carbon_get_theme_option('main_banner_1');?>
		<img src="<?php echo $banner_1 ?>" class="main__banner-img" alt="Plantis" width="150" height="26">
	</div>
	<div class="main__sale-gallery">
		<?php echo do_shortcode('[products on_sale="true" class="main-sale-slider" limit="8" columns="3" orderby="rand" category="komnatnye-rasteniya"]') ?>

		<script type="text/javascript">
            jQuery(window).load(function() {
                jQuery(".main-sale-slider > ul").flexisel({
                    visibleItems:3,
                    animationSpeed: 1000,
                    autoPlay: false,
                    autoPlaySpeed: 3000,
                    pauseOnHover: true,
                    enableResponsiveBreakpoints: true,
                    responsiveBreakpoints: {
                        portrait: {
                            changePoint:480,
                            visibleItems: 1
                        },
                        landscape: {
                            changePoint:640,
                            visibleItems:2
                        },
                        tablet: {
                            changePoint:768,
                            visibleItems: 3
                        }
                    }
                });
            });
		</script>
    </div>
			
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
