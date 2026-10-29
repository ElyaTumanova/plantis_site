<?php get_header();?>

<main id="main" class="site-main front" role="main">

  <section class="front__hero section">
    <h1 class="visually-hidden">Доставка комнатных растений в Москве</h1>
    <?php get_template_part('template-parts/front/front-banners-gallery');?>
  </section> 

  <section class="front__cat-tiles section container">
    <?php get_template_part('template-parts/front/cats-tiles');?>
  </section> 

  <section class="front__popular section container">
    <?php get_template_part('template-parts/front/cats-sliders');?>
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

  <?php
  $front_services_title = get_field( 'front_services_title' ) ?: 'Предоставляемые услуги';
  $front_services = get_field( 'front_services' );

  if ( empty( $front_services ) ) {
    $front_services = [
      [
        'image' => [
          'url' => get_template_directory_uri() . '/images/frontend/front-services-ozel.webp',
          'alt' => 'Озеленение офисов',
        ],
        'link' => [
          'url' => site_url( '/landscaping' ),
          'target' => '_blank',
        ],
        'title' => 'Озеленение офисов',
        'text' => 'Хотите озеленить офис, но не знаете, с чего начать? Поможем сделать первый шаг',
      ],
      [
        'image' => [
          'url' => get_template_directory_uri() . '/images/frontend/front-services-ukhod.webp',
          'alt' => 'Профессиональный уход за растениями',
        ],
        'link' => [
          'url' => site_url( '/professionalnyj-uhod-za-rasteniyami' ),
          'target' => '_blank',
        ],
        'title' => 'Профессиональный уход за растениями',
        'text' => 'Доверьте уход за растениями профессионалам — и просто наслаждайтесь результатом',
      ],
    ];
  }
  ?>

  <section class="section container">
    <h2 class="h2"><?php echo esc_html( $front_services_title ); ?></h2>

    <div class="front__services grid-2-cols">
      <?php foreach ( $front_services as $service ) :
        $image = ! empty( $service['image'] ) ? $service['image'] : [];
        $link = ! empty( $service['link'] ) ? $service['link'] : [];
        $title = ! empty( $service['title'] ) ? $service['title'] : '';
        $text = ! empty( $service['text'] ) ? $service['text'] : '';
        $image_url = ! empty( $image['url'] ) ? $image['url'] : '';
        $image_alt = ! empty( $image['alt'] ) ? $image['alt'] : $title;
        $link_url = ! empty( $link['url'] ) ? $link['url'] : '#';
        $link_target = ! empty( $link['target'] ) ? $link['target'] : '_self';
      ?>
        <a class="front__services-wrap" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
          <?php if ( $image_url ) : ?>
            <img
              class="front__services-image"
              src="<?php echo esc_url( $image_url ); ?>"
              alt="<?php echo esc_attr( $image_alt ); ?>"
              width="644"
              height="400"
            >
          <?php endif; ?>

          <div class="front__services-content icon icon--arrow-right">
            <div class="front__services-content-inner">
              <?php if ( $title ) : ?>
                <h3 class="h4 front__services-title"><?php echo esc_html( $title ); ?></h3>
              <?php endif; ?>

              <?php if ( $text ) : ?>
                <p class="front__services-text"><?php echo esc_html( $text ); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="section container">
    <h2 class="h2">Наши преимущества</h2>
    <?php get_template_part( 'template-parts/advantages' );?>

  </section>

  <?php if ( have_rows( 'promo-banners' ) ) : ?>

    <section class="section container front__promo grid-2-cols">
      <h2 class="visually-hidden">Специальные предложения</h2>

      <?php while ( have_rows( 'promo-banners' ) ) : the_row();

        $link          = get_sub_field( 'link' );
        $imageDesktop  = get_sub_field( 'image-desktop' );
        $imageMob      = get_sub_field( 'image-mob' );
      ?>

        <a
          class="front__promo-link"
          href="<?php echo esc_url( $link['url'] ); ?>"
          <?php echo $link['target'] ? 'target="' . esc_attr( $link['target'] ) . '"' : ''; ?>
        >
          <picture class="front__promo-image">

            <?php if ( $imageMob ) : ?>
              <source
                media="(max-width: 500px)"
                srcset="<?php echo esc_url( $imageMob['url'] ); ?>"
              >
            <?php endif; ?>

            <img
              src="<?php echo esc_url( $imageDesktop['url'] ); ?>"
              alt="<?php echo esc_attr( $imageDesktop['alt'] ); ?>"
              width="<?php echo esc_attr( $imageDesktop['width'] ); ?>"
              height="<?php echo esc_attr( $imageDesktop['height'] ); ?>"
              loading="lazy"
            >

          </picture>
        </a>

      <?php endwhile; ?>

    </section>

  <?php endif; ?>

  <section class="section container">
    <div class="front__about">
      <img 
      class="front__about-image"
      src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/front-about.webp' ); ?>" 
      alt="Plantis.shop — интернет-магазин комнатных растений с доставкой"
      width="1300"
      height="720">
      <h2 class="front__about-heading h2">Plantis.shop — интернет-магазин комнатных растений с доставкой</h2>
      <p class="front__about-text">Добро пожаловать в Plantis — интернет-магазин комнатных растений с быстрой доставкой по Москве и Московской области. 
        У нас можно заказать комнатные растения онлайн или выбрать их в нашем шоуруме. 
        Мы предлагаем эксклюзивный ассортимент горшечных растений и высокий уровень сервиса, чтобы ваша покупка была приятной.</p>
    </div>
  </section>

  <section class="section container">
    <?php get_template_part('template-parts/front/front-assortiment');?>
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

<?php get_footer(); ?>

