<?php
    get_header();
    global $skidki_cat_id;
?>


<main id="main" class="site-main front" role="main">

  <section class="front__hero section">
    <h1 class="visually-hidden">Доставка комнатных растений в Москве</h1>
    <?php get_template_part('template-parts/front/front-banners-gallery');?>
  </section>
  
  <section class="front__cat-tiles section container <?php echo current_user_can( 'administrator' ) ? '' : ' d-none'; ?>">
    <?php get_template_part('template-parts/front/cats-tiles');?>
  </section>

  <section class="front__skidki section container">
    <h2 class="h2">Скидки</h2>
    
    <?php 
      get_template_part( 'template-parts/products/product-slider', null, [
        'queryArgs' => [
          'tax_query' => array(
            array(
              'taxonomy' => 'product_tag',
              'field' => 'slug',
              'terms' => 'skidki',
            )
          )
        ],
        'isSwiperOver' => true,
      ]);
    ?>

    <a class="front__products-all icon icon--arrow-right" href="<?php echo get_term_link( 'skidki', 'product_tag' );?>">Все товары категории</a>
    
  </section>

  <section class="front__popular section container">
    <h2 class="h2">Популярные категории</h2>
    <div class="front__cats-nav cats-nav">
      <button class="button cats-nav__title cats-nav__title--active" type="button" data-type="product_cat" data-term="dekorativno-cvetushchie">Цветущие</button>
      <button class="button cats-nav__title" type="button" data-type="product_cat" data-term="fikusy">Фикусы</button>
      <button class="button cats-nav__title" type="button" data-type="product_tag" data-term="napolnye">Напольные</button>
      <button class="button cats-nav__title" type="button" data-type="product_cat" data-term="palms">Пальмы</button>
      <button class="button cats-nav__title" type="button" data-type="product_tag" data-term="novichkam">Неприхотливые</button>
      <button class="button cats-nav__title" type="button" data-type="product_cat" data-term="dekorativno-listvennye">Декоративно-лиственные</button>
      <button class="button cats-nav__title" type="button" data-type="product_cat" data-term="succulent">Суккуленты</button>
      <button class="button cats-nav__title" type="button" data-type="product_tag" data-term="pet-friendly">Pet friendly</button>
      <button class="button cats-nav__title" type="button" data-type="product_cat" data-term="lianas">Лианы</button>
    </div>


   <?php 
      get_template_part( 'template-parts/products/product-slider', null, [
        'queryArgs' => [
          'tax_query' => array(
            array(
              'taxonomy' => 'product_cat',
              'field' => 'slug',
              'terms' => 'dekorativno-cvetushchie',
            )
          )
        ],
        'isSwiperOver' => true,
      ]);
    ?>

    <a class="front__products-all icon icon--arrow-right" href="<?php echo get_term_link( 'dekorativno-cvetushchie', 'product_cat' );?>">Все товары категории</a>

  </section>

  <section class="section container">
    <h2 class="h2">Предоставляемые услуги</h2>
    <div class="front__services grid-2-cols">
      <a class="front__services-wrap"
        href="<?php echo esc_url(site_url() . '/landscaping')?>"
        target="_blank"
      >
        <img class="front__services-image"
        src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-services-ozel.jpg' ); ?>" 
        alt="Озеленение офисов"
        width="644"
        height="400"
        >
        <div class="front__services-content icon icon--arrow-right">
          <div class="front__services-content-inner">
            <h3 class="h4 front__services-title">Озеленение офисов</h3>
            <p class="front__services-text">Хотите озеленить офис, но не знаете, с чего начать? Поможем сделать первый шаг</p>
          </div>
        </div>
      </a>
      <a class="front__services-wrap"
        href="<?php echo esc_url(site_url() . '/professionalnyj-uhod-za-rasteniyami')?>"
        target="_blank"
      >
        <img class="front__services-image"
        src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-services-ukhod.jpg' ); ?>" 
        alt="Профессиональный уход за растениями"
        width="644"
        height="400"
        >
        <div class="front__services-content icon icon--arrow-right">
          <div class="front__services-content-inner">
            <h3 class="h4 front__services-title">Профессиональный уход за растениями</h3>
            <p class="front__services-text">Доверьте уход за растениями профессионалам — и просто наслаждайтесь результатом</p>
          </div>
        </div>
      </a>
    </div>
  </section>

  <section class="section container">
    <h2 class="h2">Наши преимущества</h2>
    <?php get_template_part( 'template-parts/advantages' );?>

  </section>

  <section class="section container front__promo grid-2-cols">
    <h2 class="visually-hidden">Специальные предложения</h2>
    <a class="front__promo-link"
        href="<?php echo esc_url(site_url() . '/shop/gift-card/')?>"
        target="_blank"
      >
        <picture class="front__promo-image">
          <source
            media="(max-width: 500px)"
            srcset="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-promo-giftcard-mob.jpg' ); ?>"
          >

          <img
            src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-promo-giftcard.jpg' ); ?>"
            alt="Подарочный сертификат"
            width="644"
            height="260"
            loading="lazy"
          >
        </picture>
    </a>
    <a class="front__promo-link"
        href="<?php echo esc_url(site_url() . '/test-kakoe-ty-rastenie')?>"
        target="_blank"
      >
        <picture class="front__promo-image">
          <source
            media="(max-width: 500px)"
            srcset="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-promo-test-mob.jpg' ); ?>"
          >

          <img
            src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-promo-test.jpg' ); ?>"
            alt="Подарочный сертификат"
            width="644"
            height="260"
            loading="lazy"
          >
        </picture>
    </a>

  </section>

  <section class="section container">
    <div class="front__about">
      <img 
      class="front__about-image"
      src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-about.jpg' ); ?>" 
      alt="Plantis.shop — интернет-магазин комнатных растений с доставкой"
      width="1300"
      height="720">
      <h2 class="front__about-heading h2">Plantis.shop — интернет-магазин комнатных растений с доставкой</h2>
      <p class="front__about-text">Добро пожаловать в Plantis — интернет-магазин комнатных растений с быстрой доставкой по Москве и Московской области. У нас можно заказать комнатные растения онлайн или выбрать их в нашем шоуруме оффлайн Мы предлагаем эксклюзивный ассортимент горшечных растений и высокий уровень сервиса, чтобы ваша покупка была приятной.</p>
    </div>
  </section>

  <section class="section container">
    <h2 class="h2">Ассортимент Plantis.shop включает:</h2>
    <div class="front__assort swiper--over">
      <div class="swiper">
        <div class="front__assort-slider swiper-wrapper">
          <div class="front__assort-wrap darken swiper-slide">
            <div class="front__assort-content">
              <h3 class="h5 front__assort-heading">Для офиса</h3>
              <p class="front__assort-text">Неприхотливые растения с лаконичным видом — для рабочих зон, переговорных и ресепшенов</p>
            </div>
            <img class="front__assort-image"
              src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-assort-office.webp' ); ?>"
              alt="Неприхотливые растения для офиса"
              width="316"
              height="316"
            >
          </div>
          <div class="front__assort-wrap darken swiper-slide">
            <div class="front__assort-content">
              <h3 class="h5 front__assort-heading">Экзотические</h3>
              <p class="front__assort-text">Редкие и выразительные растения с необычной листвой — для ярких акцентов в интерьере</p>
            </div>
            <img class="front__assort-image"
              src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-assort-exotic.webp' ); ?>"
              alt="Экзотические растения с необычной листвой"
              width="316"
              height="316"
            >
          </div>
          <div class="front__assort-wrap darken swiper-slide">
            <div class="front__assort-content">
              <h3 class="h5 front__assort-heading">Бонсаи</h3>
              <p class="front__assort-text">Миниатюрные деревья с декоративной формой кроны — для стильных подарков и спокойных интерьеров</p>
            </div>
            <img class="front__assort-image"
              src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-assort-bonsai.webp' ); ?>"
              alt="Миниатюрные деревья бонсаи"
              width="316"
              height="316"
            >
          </div>
          <div class="front__assort-wrap darken swiper-slide">
            <div class="front__assort-content">
              <h3 class="h5 front__assort-heading">Комнатные пальмы и крупномеры</h3>
              <p class="front__assort-text">Арека, фикусы, драцены — для просторных помещений и эффектного озеленения</p>
            </div>
            <img class="front__assort-image"
              src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-assort-large.webp' ); ?>"
              alt="Комнатные пальмы и крупномеры"
              width="316"
              height="316"
            >
          </div>
          <div class="front__assort-wrap darken swiper-slide">
            <div class="front__assort-content">
              <h3 class="h5 front__assort-heading">Лианы и ампельные растения</h3>
              <p class="front__assort-text">Сциндапсусы, филодендроны, эпипремнумы — для вертикального озеленения</p>
            </div>
            <img class="front__assort-image"
              src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-assort-ampel.webp' ); ?>"
              alt="Лианы и ампельные растения"
              width="316"
              height="316"
            >
          </div>
          <div class="front__assort-wrap darken swiper-slide">
            <div class="front__assort-content">
              <h3 class="h5 front__assort-heading">Теневыносливые</h3>
              <p class="front__assort-text">Замиокулькасы, сансевиерии, аглаонемы — для помещений с минимальным количеством света</p>
            </div>
            <img class="front__assort-image"
              src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-assort-shadow.webp' ); ?>"
              alt="Теневыносливые комнатные растения"
              width="316"
              height="316"
            >
          </div>
          <div class="front__assort-wrap darken swiper-slide">
            <div class="front__assort-content">
              <h3 class="h5 front__assort-heading">Цитрусовые</h3>
              <p class="front__assort-text">Каламондины, кумкваты, лимоны — для создания солнечного настроения вашего домашнего мини-сада</p>
            </div>
            <img class="front__assort-image"
              src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-assort-citra.webp' ); ?>"
              alt="Цитрусовые растения для домашнего мини-сада"
              width="316"
              height="316"
            >
          </div>
        </div>
        <div class="swiper-scrollbar"></div>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </section>

  <section class="section container front__description grid-2-cols">
    <h2 class="visually-hidden">Почему выбирают Plantis.shop</h2>
    <div class="front__description-wrap">
      <h3 class="h4 front__description-title">
        Комнатные растения для дома, офиса и бизнеса
      </h3>
      <div class="front__description-text">
        <p>В нашем каталоге представлены горшечные растения, которые подойдут как для уютной квартиры, так и для стильного офиса, ресторана, салона красоты или бизнес-центра.</p>
        <p>Мы работаем как с физическими, так и с юридическими лицами, предлагая выгодные условия для корпоративных клиентов.</p>
      </div>
    </div>
    <div class="front__description-wrap">
      <h3 class="h4 front__description-title">
        Plantis.shop — ваш эксперт по комнатным растениям
      </h3>
      <div class="front__description-text">
        <p>Plantis.shop — это не просто магазин, это команда экспертов, которые помогут выбрать идеальное растение под ваши цели, условия, образ жизни.</p>
        <p>Станьте частью зелёного сообщества Plantis. Мы — место, где легко и приятно заказать комнатные растения с доставкой, получая удовольствие от сервиса. Сервиса, к которому хочется возвращаться.</p>
      </div>
    </div>
  </section>

</main>

<?php $start_footer = microtime(true); get_footer(); ?>
<?php echo "<!-- Timing: get_footer = " . round((microtime(true) - $start_footer) * 1000, 2) . " ms -->"; ?>

