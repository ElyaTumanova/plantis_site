<?php get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main gc-info" role="main">
    <section class="gc-info__head">
      <div class="gc-info__head-inner" >
        <h1 class="gc-info__title h1">Подарок, который точно понравится</h1>
        <p class="gc-info__description">Идеальный подарок для тех, кто ценит стиль, уют и эстетичные детали. Выберите номинал карты и подарите возможность выбрать то, что действительно понравится</p>
      </div>
      <img
					class="gc-info__image"
					src="<?php echo esc_url( get_template_directory_uri() . '/images/gift-card/gc-info-cover.jpg' ); ?>"
					alt=""
					width="490"
					height="302"
				>
      <div class="gc-info__circle"></div>
    </section>
    
    <?php get_template_part( 'template-parts/gift-card/gift-card-advantages' );?>
    <?php get_template_part( 'template-parts/gift-card/gift-card-faq' );?>


    
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer();?>

