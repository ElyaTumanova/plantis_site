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

    <section class="gift-card__info section">
      <h2 class="giftcard-advantages__title h2">Преимущества подарочного сертификата</h2>
      <div class="giftcard-advantages">
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__icon">
            <?php echo plnt_icon('octagon');?>
          </span>
          <div class="giftcard-advantages__descr">Подходит для физлиц и корпоративных клиентов</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__icon">
            <?php echo plnt_icon('wallet');?>
          </span>
          <div class="giftcard-advantages__descr">Гибкий номинал сертификата от 1500 до 30 000 рублей</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__icon">
            <?php echo plnt_icon('ticket');?>
          </span>
          <div class="giftcard-advantages__descr">Можно использовать сертификат онлайн или в шоурумах</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__icon">
            <?php echo plnt_icon('percent');?>
          </span>
          <div class="giftcard-advantages__descr">Можно оплачивать товары и услуги со скидкой</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__icon">
            <?php echo plnt_icon('timer');?>
          </span>
          <div class="giftcard-advantages__descr">Можно использовать многократно в рамках номинала и срока действия</div>
        </div>
        <div class="giftcard-advantages__wrap">
          <span class="giftcard-advantages__icon">
            <?php echo plnt_icon('heart');?>
          </span>
          <div class="giftcard-advantages__descr">Удобно, если вы не знаете, какое именно растение подойдет </div>
        </div>
      </div>
    </section>

    <section>
      <h2 class="giftcard-advantages__title h2">Часто задаваемые вопросы</h2>
      <?php get_template_part( 'template-parts/gift-card-faq' );?>
    </section>
    
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer();?>

