<?php

get_header(); ?>



<div class="content-area">
	<main id="main" class="site-main" role="main">

	<div class="main__banners-wrap">
		<?php get_template_part('template-parts/main-banners-gallery');?>
	</div>


	
	<div class="main__sale-gallery">
		<?php echo do_shortcode('[products on_sale="true" class="main-sale-slider" limit="8" columns="1" orderby="rand" category="komnatnye-rasteniya"]') ?>

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
                            visibleItems:1
                        },
                        tablet: {
                            changePoint:768,
                            visibleItems: 1
                        }
                    }
                });
            });
		</script>
    </div>
			
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>
