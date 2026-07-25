<?php
get_header();

$pricelist_link = carbon_get_theme_option( 'pricelist_link' );

$catalog_link = wc_get_page_permalink( 'shop' );

global $plants_cat_id;

if ( ! empty( $plants_cat_id ) ) {
  $plants_term_link = get_term_link( $plants_cat_id, 'product_cat' );

  if ( ! is_wp_error( $plants_term_link ) ) {
    $catalog_link = $plants_term_link;
  }
}
?>

<div class="content-area">
  <main id="main" class="site-main page-ukhod page-optom" role="main">

    <section class="section page-ukhod__first-screen">
      <div class="page-ukhod__first-screen-wrap">
        <div class="page-ukhod__first-screen-inner">
          <h1 class="page-ukhod__entry-title h1">
            Комнатные растения<br>
            оптом
          </h1>

          <div class="page-ukhod__intro">
            Подберём растения под вашу задачу, подготовим индивидуальное предложение и организуем поставку. Работаем с дизайнерами, озеленителями, компаниями и коммерческими пространствами.
          </div>

          <div class="page-ukhod__rasschet">
            <button
              class="button button--green page-popup-open-btn"
              name="Получить оптовое предложение с первого экрана"
            >
              Получить предложение
            </button>

            <div class="page-ukhod__rasschet-socials-wrap">
              <span>Или напишите нам <br>в мессенджеры</span>
              <?php get_template_part( 'template-parts/social-media-btns' ); ?>
            </div>
          </div>
        </div>

        <picture>
          <source
            media="(max-width: 768px)"
            srcset="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod-hero-mob.png' ); ?>"
          >
          <img
            class="page-ukhod__photo"
            loading="lazy"
            src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod-hero-desktop.png' ); ?>"
            alt="Оптовая поставка комнатных растений"
          >
        </picture>
      </div>

      <div class="page-ukhod__intro_small">
        <p>Нужен надёжный поставщик растений для проекта, офиса, магазина или другого пространства?</p>
        <p>Поможем собрать заказ и взять организацию поставки на себя.</p>
      </div>
    </section>

    <section class="section page-ukhod__services page-ukhod__stages">
      <div class="page-ukhod__stages-col">
        <h2 class="h2 page-ukhod__services-title">Как проходит оптовая закупка</h2>

        <img
          class="page-ukhod__stages-image mobile-hidden"
          loading="lazy"
          src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod.png' ); ?>"
          alt="Подготовка оптовой поставки комнатных растений"
        >
      </div>

      <ul class="page-ukhod__services-list">
        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">01</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Заявка</h3>
            <p class="page-ukhod__services-item-descr">Вы рассказываете, какие растения нужны, для какого пространства и в каком количестве.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">02</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Уточнение задачи</h3>
            <p class="page-ukhod__services-item-descr">Уточняем бюджет, желаемые размеры, сроки, условия размещения и требования к ассортименту.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">03</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Подбор растений</h3>
            <p class="page-ukhod__services-item-descr">Проверяем наличие и подбираем подходящие позиции из текущего ассортимента и поставок питомников.</p>
          </div>
        </li>

        <img
          class="page-ukhod__stages-image desktop-hidden"
          loading="lazy"
          src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod.png' ); ?>"
          alt="Подготовка оптовой поставки комнатных растений"
        >

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">04</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Коммерческое предложение</h3>
            <p class="page-ukhod__services-item-descr">Формируем предложение с ассортиментом, количеством, стоимостью, скидкой и ориентировочным сроком поставки.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">05</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Согласование заказа</h3>
            <p class="page-ukhod__services-item-descr">Утверждаем состав заказа, фиксируем условия и передаём его на комплектацию после оплаты.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">06</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Комплектация и доставка</h3>
            <p class="page-ukhod__services-item-descr">Проверяем растения перед отправкой, комплектуем поставку и согласовываем удобное время доставки.</p>
          </div>
        </li>
      </ul>
    </section>

    <section class="section page-ukhod__services page-ukhod__included">
      <h2 class="h2 page-ukhod__services-title">Что вы получаете</h2>

      <ul class="page-ukhod__included-list">
        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-1' ); ?>
          </span>
          <p class="page-ukhod__included-text">Помощь с подбором ассортимента</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-2' ); ?>
          </span>
          <p class="page-ukhod__included-text">Здоровые растения от проверенных питомников</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'buildings' ); ?>
          </span>
          <p class="page-ukhod__included-text">Подбор размеров и количества под проект</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'octagon' ); ?>
          </span>
          <p class="page-ukhod__included-text">Индивидуальное коммерческое предложение</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'percent' ); ?>
          </span>
          <p class="page-ukhod__included-text">Оптовая скидка в зависимости от суммы заказа</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-6' ); ?>
          </span>
          <p class="page-ukhod__included-text">Проверка растений перед отправкой</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'car-grad' ); ?>
          </span>
          <p class="page-ukhod__included-text">Согласование сроков и организация доставки</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-8' ); ?>
          </span>
          <p class="page-ukhod__included-text">Консультация и поддержка после покупки</p>
        </li>
      </ul>
    </section>

    <section class="section page-ukhod__tariffs page-optom__discounts">
      <h2 class="h2 page-ukhod__tariffs-title">Система оптовых скидок</h2>
      <p class="page-optom__discounts-intro">Размер скидки зависит от итоговой стоимости горшечных растений в заказе.</p>

      <div class="page-ukhod__tariffs-items">
        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">от 50 000 ₽</span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">Скидка 5%</h3>

            <p class="page-ukhod__tariffs-item-descr">
              Подойдёт для небольшого проекта, локального озеленения офиса, кабинета, студии или коммерческого пространства.
            </p>

            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Получить оптовое предложение со скидкой 5 процентов"
            >
              Получить предложение
            </button>
          </div>
        </div>

        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">от 100 000 ₽</span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">Скидка 10%</h3>

            <p class="page-ukhod__tariffs-item-descr">
              Для комплексного наполнения офиса, ресторана, салона, магазина или нескольких зон одного объекта.
            </p>

            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Получить оптовое предложение со скидкой 10 процентов"
            >
              Получить предложение
            </button>
          </div>
        </div>

        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">от 200 000 ₽</span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">Скидка 15%</h3>

            <p class="page-ukhod__tariffs-item-descr">
              Для крупных проектов, регулярных закупок и комплектации больших коммерческих пространств растениями.
            </p>

            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Получить оптовое предложение со скидкой 15 процентов"
            >
              Получить предложение
            </button>
          </div>
        </div>
      </div>

      <p class="page-optom__discounts-note">Скидка распространяется только на покупку горшечных растений.</p>
    </section>

    <section class="section page-optom__terms">
      <div class="page-optom__terms-content">
        <span class="page-optom__terms-icon">
          <?php echo plnt_icon( 'verify' ); ?>
        </span>

        <div>
          <h2 class="h2 page-optom__terms-title">Срок поставки — от 3 до 20 дней</h2>
          <p class="page-optom__terms-descr">Точный срок зависит от количества растений, текущего наличия и доступности выбранных позиций в питомниках. Мы сообщим ориентировочную дату до подтверждения заказа.</p>
        </div>
      </div>
    </section>

    <section class="section page-optom__cta">
      <div class="page-optom__cta-content">
        <h2 class="h2 page-optom__cta-title">Готовы собрать оптовый заказ?</h2>
        <p class="page-optom__cta-descr">Посмотрите текущий ассортимент или скачайте оптовый прайс-лист. Для индивидуального подбора оставьте заявку — мы подготовим предложение под вашу задачу.</p>
      </div>

      <div class="page-optom__cta-actions">
        <a
          class="button button--green-l page-optom__cta-button"
          href="<?php echo esc_url( $catalog_link ); ?>"
        >
          Каталог растений
        </a>

        <?php if ( $pricelist_link ) : ?>
          <a
            class="button page-optom__cta-button button--green-l"
            href="<?php echo esc_url( $pricelist_link ); ?>"
          >
            Скачать оптовый прайс-лист
          </a>
        <?php else : ?>
          <button
            class="button page-optom__cta-button button--green-l page-popup-open-btn"
            name="Получить оптовый прайс-лист"
          >
            Получить оптовый прайс-лист
          </button>
        <?php endif; ?>
      </div>
    </section>

    <section class="section page-ukhod__advantages">
      <h2 class="h2 page-ukhod__advantages-title">Почему оптовые клиенты выбирают Plantis?</h2>

      <div class="advantages">
        <div class="advantages__wrap">
          <?php echo plnt_icon( 'leaf' ); ?>
          <h3 class="h5 advantages__title">Здоровые растения</h3>
          <p class="advantages__descr">Работаем с проверенными поставщиками и внимательно проверяем растения перед отправкой.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'verify' ); ?>
          <h3 class="h5 advantages__title">Понятные условия</h3>
          <p class="advantages__descr">Заранее согласовываем состав заказа, стоимость, скидку и ориентировочный срок поставки.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'box' ); ?>
          <h3 class="h5 advantages__title">Помощь с подбором</h3>
          <p class="advantages__descr">Подскажем подходящие виды и размеры растений с учётом пространства, бюджета и задачи.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'buildings' ); ?>
          <h3 class="h5 advantages__title">Работаем с разными объёмами</h3>
          <p class="advantages__descr">Комплектуем небольшие локальные проекты и крупные поставки для коммерческих объектов.</p>
        </div>
      </div>
    </section>

  </main>
</div>

<?php
get_template_part( 'template-parts/popups/service-popup' );
get_footer();
?>
