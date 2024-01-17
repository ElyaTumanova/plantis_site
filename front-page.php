<?php

get_header(); ?>

<div class="content-area">
	<div>может быть это главная?</div>
	<main id="main" class="site-main" role="main">

	<div class="main__banners">
		<?php $banner_1 = carbon_get_theme_option('main_banner_1');?>
		<?php $banner_1_name = carbon_get_theme_option('main_banner_1_name');?>
		<?php $banner_2 = carbon_get_theme_option('main_banner_2');?>
		<?php $banner_2_name = carbon_get_theme_option('main_banner_2_name');?>
		<?php $banner_3 = carbon_get_theme_option('main_banner_3');?>
		<?php $banner_3_name = carbon_get_theme_option('main_banner_3_name');?>
		<?php $banner_4 = carbon_get_theme_option('main_banner_4');?>
		<?php $banner_4_name = carbon_get_theme_option('main_banner_4_name');?>

		<script>
		console.log (<?php echo $banner_1 ?>);
		console.log (<?php echo $banner_4 ?>);
		</script>
		<img src="<?php echo $banner_1 ?>" class="main__banner-img" alt="<?php echo $banner_1_name?>" width="900" height="280">
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
