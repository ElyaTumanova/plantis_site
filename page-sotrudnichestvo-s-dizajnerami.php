<?php
get_header();

/* Размер бонуса можно изменить в одном месте. */
$designer_bonus_percent = 10;
$catalog_link            = wc_get_page_permalink( 'shop' );

global $plants_cat_id;

if ( ! empty( $plants_cat_id ) ) {
  $plants_term_link = get_term_link( $plants_cat_id, 'product_cat' );

  if ( ! is_wp_error( $plants_term_link ) ) {
    $catalog_link = $plants_term_link;
  }
}
?>

<div class="content-area">
  <main id="main" class="site-main page-ukhod page-designers" role="main">

    <?php
    $service_hero_title = get_field( 'service_hero_title' ) ?: "Сотрудничество\nс дизайнерами";

    $service_hero_intro = get_field( 'service_hero_intro' ) ?: 'Поможем подобрать растения для вашего проекта, подготовим предложение для клиента и организуем поставку. За реализованный заказ вы получите бонус от стоимости приобретённых растений.';

    $service_hero_bottom_text = get_field( 'service_hero_bottom_text' ) ?: 'Вы создаёте интерьер — мы берём на себя подбор, комплектацию и поставку растений.';

    $service_hero_bottom_accent = get_field( 'service_hero_bottom_accent' ) ?: 'Клиент получает готовое решение, а вы — партнёрское вознаграждение.';

    $service_hero_image_desktop = get_field( 'service_hero_image_desktop' );
    $service_hero_desktop_url = ! empty( $service_hero_image_desktop['url'] ) ? $service_hero_image_desktop['url'] : get_template_directory_uri() . '/images/frontend/ukhod-hero-desktop.png';

    $service_hero_image_mobile = get_field( 'service_hero_image_mobile' );
    $service_hero_mobile_url = ! empty( $service_hero_image_mobile['url'] ) ? $service_hero_image_mobile['url'] : get_template_directory_uri() . '/images/frontend/ukhod-hero-mob.png';

    $service_hero_image_alt = get_field( 'service_hero_image_alt' );
    $service_hero_image_alt = ! empty( $service_hero_image_desktop['alt'] ) ? $service_hero_image_desktop['alt'] : $service_hero_image_alt;
    $service_hero_image_alt = $service_hero_image_alt ?: 'Подбор комнатных растений для дизайнерского проекта';
    ?>

    <section class="section page-ukhod__first-screen">
      <div class="page-ukhod__first-screen-wrap">
        <div class="page-ukhod__first-screen-inner">
          <h1 class="page-ukhod__entry-title h1">
            <?php echo nl2br( esc_html( $service_hero_title ) ); ?>
          </h1>

          <div class="page-ukhod__intro">
            <?php echo esc_html( $service_hero_intro ); ?>
          </div>

          <div class="page-ukhod__rasschet">
            <button class="button button--green page-popup-open-btn" name="Стать партнёром Plantis с первого экрана">
              Стать партнёром
            </button>

            <div class="page-ukhod__rasschet-socials-wrap">
              <span>Или напишите нам <br>в мессенджеры</span>
              <?php get_template_part( 'template-parts/social-media-btns' ); ?>
            </div>
          </div>
        </div>

        <picture>
          <source media="(max-width: 768px)" srcset="<?php echo esc_url( $service_hero_mobile_url ); ?>">
          <img
            class="page-ukhod__photo"
            loading="lazy"
            src="<?php echo esc_url( $service_hero_desktop_url ); ?>"
            alt="<?php echo esc_attr( $service_hero_image_alt ); ?>"
          >
        </picture>
      </div>

      <div class="page-ukhod__intro_small">
        <p><?php echo esc_html( $service_hero_bottom_text ); ?></p>
        <p><?php echo esc_html( $service_hero_bottom_accent ); ?></p>
      </div>
    </section>

    <section class="section page-ukhod__services page-ukhod__stages">
      <div class="page-ukhod__stages-col">
        <h2 class="h2 page-ukhod__services-title">Как проходит сотрудничество</h2>

        <img
          class="page-ukhod__stages-image mobile-hidden"
          loading="lazy"
          src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod.png' ); ?>"
          alt="Сотрудничество дизайнера с Plantis"
        >
      </div>

      <ul class="page-ukhod__services-list">
        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">01</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Знакомство</h3>
            <p class="page-ukhod__services-item-descr">Вы оставляете заявку, а мы уточняем формат вашей работы, тип проектов и удобный способ взаимодействия.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">02</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Фиксируем условия</h3>
            <p class="page-ukhod__services-item-descr">Согласовываем размер бонуса, порядок передачи проектов и выплаты вознаграждения, затем оформляем договор.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">03</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Получаем задачу</h3>
            <p class="page-ukhod__services-item-descr">Вы передаёте нам техническое задание, визуализации, размеры, бюджет и информацию об условиях в помещении.</p>
          </div>
        </li>

        <img
          class="page-ukhod__stages-image desktop-hidden"
          loading="lazy"
          src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod.png' ); ?>"
          alt="Сотрудничество дизайнера с Plantis"
        >

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">04</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Подбираем растения</h3>
            <p class="page-ukhod__services-item-descr">Предлагаем подходящие виды и размеры с учётом концепции интерьера, освещения, бюджета и будущего ухода.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">05</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Работаем с клиентом</h3>
            <p class="page-ukhod__services-item-descr">Plantis согласовывает ассортимент, принимает оплату, оформляет продажу и организует доставку напрямую клиенту.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">06</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Выплачиваем бонус</h3>
            <p class="page-ukhod__services-item-descr">После полной оплаты и выполнения заказа рассчитываем вознаграждение от фактической стоимости приобретённых растений.</p>
          </div>
        </li>
      </ul>
    </section>

    <section class="section page-ukhod__services page-ukhod__included">
      <h2 class="h2 page-ukhod__services-title">Что получает дизайнер</h2>

      <ul class="page-ukhod__included-list">
        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-1' ); ?>
          </span>
          <p class="page-ukhod__included-text">Персонального менеджера по проекту</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-2' ); ?>
          </span>
          <p class="page-ukhod__included-text">Подбор растений по техническому заданию</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-3' ); ?>
          </span>
          <p class="page-ukhod__included-text">Фотографии, размеры и информацию по позициям</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-4' ); ?>
          </span>
          <p class="page-ukhod__included-text">Коммерческое предложение для согласования</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-5' ); ?>
          </span>
          <p class="page-ukhod__included-text">Проверку качества растений перед отправкой</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-6' ); ?>
          </span>
          <p class="page-ukhod__included-text">Организацию доставки и дополнительных услуг</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-7' ); ?>
          </span>
          <p class="page-ukhod__included-text">Поддержку клиента после покупки</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-8' ); ?>
          </span>
          <p class="page-ukhod__included-text">Партнёрский бонус за реализованный заказ</p>
        </li>
      </ul>
    </section>

    <section class="section page-ukhod__tariffs page-designers__bonus">
      <h2 class="h2 page-ukhod__tariffs-title">Как рассчитывается бонус</h2>
      <p class="page-designers__bonus-intro">Все основные условия фиксируются в договоре до начала работы с первым проектом.</p>

      <div class="page-ukhod__tariffs-items">
        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">
                <?php echo esc_html( $designer_bonus_percent ); ?>% от стоимости растений
              </span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">Размер вознаграждения</h3>

            <p class="page-ukhod__tariffs-item-descr">
              Бонус рассчитывается от фактически оплаченной клиентом стоимости растений с учётом применённых скидок и изменений в составе заказа.
            </p>

            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Узнать условия бонусной программы для дизайнеров"
            >
              Узнать условия
            </button>
          </div>
        </div>

        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-period">только растения</span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">Что участвует в расчёте</h3>

            <p class="page-ukhod__tariffs-item-descr">
              В бонусную базу входит стоимость комнатных растений. Горшки, кашпо, доставка, пересадка, уход и другие услуги в расчёте не участвуют.
            </p>

            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Обсудить проект с дизайнером"
            >
              Обсудить проект
            </button>
          </div>
        </div>

        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-period">после выполнения заказа</span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">Когда выплачивается бонус</h3>

            <p class="page-ukhod__tariffs-item-descr">
              Вознаграждение выплачивается после полной оплаты и передачи заказа клиенту. Срок, способ выплаты и подтверждающие документы указываются в договоре.
            </p>

            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Стать партнёром программы для дизайнеров"
            >
              Стать партнёром
            </button>
          </div>
        </div>
      </div>

      <p class="page-designers__bonus-note">При отмене заказа, возврате части растений или изменении состава покупки бонус пересчитывается по фактически выполненному заказу.</p>
    </section>

    <section class="section page-designers__legal">
      <div class="page-designers__legal-heading">
        <span class="page-designers__legal-icon">
          <?php echo plnt_icon( 'verify' ); ?>
        </span>

        <div>
          <h2 class="h2 page-designers__legal-title">Как оформляем сотрудничество</h2>
          <p class="page-designers__legal-intro">Без сложной схемы: заранее фиксируем роли сторон, порядок расчёта бонуса и документы для выплаты.</p>
        </div>
      </div>

      <div class="page-designers__legal-items">
        <article class="page-designers__legal-item">
          <h3 class="h5 page-designers__legal-item-title">Агентский договор</h3>
          <p>Базовый формат — агентский договор. Дизайнер знакомит нас с клиентом и помогает организовать взаимодействие, а Plantis выплачивает агентское вознаграждение.</p>
        </article>

        <article class="page-designers__legal-item">
          <h3 class="h5 page-designers__legal-item-title">Продажу оформляет Plantis</h3>
          <p>Мы самостоятельно заключаем сделку с клиентом, принимаем оплату, выдаём документы и отвечаем за комплектацию и поставку заказа.</p>
        </article>

        <article class="page-designers__legal-item">
          <h3 class="h5 page-designers__legal-item-title">Статус партнёра</h3>
          <p>Работаем с ИП, самозанятыми и физическими лицами. Набор документов и порядок налогового оформления зависят от статуса дизайнера.</p>
        </article>

        <article class="page-designers__legal-item">
          <h3 class="h5 page-designers__legal-item-title">Контакты клиента</h3>
          <p>Передавайте контактные данные клиента только после того, как он согласился на связь с Plantis. Можно также попросить клиента обратиться к нам самостоятельно.</p>
        </article>
      </div>

      <p class="page-designers__legal-note">Конкретная форма договора может быть скорректирована под фактическую модель сотрудничества и статус партнёра.</p>
    </section>

    <section class="section page-ukhod__advantages">
      <h2 class="h2 page-ukhod__advantages-title">Почему дизайнерам удобно работать с Plantis?</h2>

      <div class="advantages">
        <div class="advantages__wrap">
          <?php echo plnt_icon( 'leaf' ); ?>
          <h3 class="h5 advantages__title">Понимаем растения</h3>
          <p class="advantages__descr">Подбираем виды не только по внешнему виду, но и по реальным условиям будущего размещения.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'verify' ); ?>
          <h3 class="h5 advantages__title">Соблюдаем концепцию</h3>
          <p class="advantages__descr">Работаем по вашему техническому заданию и не меняем утверждённое решение без согласования.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'home' ); ?>
          <h3 class="h5 advantages__title">Берём реализацию на себя</h3>
          <p class="advantages__descr">Общаемся с клиентом, комплектуем заказ и организуем доставку, пересадку и расстановку.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'buildings' ); ?>
          <h3 class="h5 advantages__title">Работаем с разными проектами</h3>
          <p class="advantages__descr">Помогаем с квартирами, офисами, ресторанами, салонами и крупными коммерческими пространствами.</p>
        </div>
      </div>
    </section>

    <section class="section page-designers__cta">
      <div class="page-designers__cta-content">
        <h2 class="h2 page-designers__cta-title">Есть проект с растениями?</h2>
        <p class="page-designers__cta-descr">Пришлите план, визуализацию или краткое описание задачи. Мы предложим ассортимент и расскажем, как подключиться к партнёрской программе.</p>
      </div>

      <div class="page-designers__cta-actions">
        <button
          class="button button--green page-designers__cta-button page-popup-open-btn"
          name="Стать партнёром Plantis из финального блока"
        >
          Стать партнёром
        </button>

        <a
          class="button page-designers__cta-button page-designers__cta-button--secondary"
          href="<?php echo esc_url( $catalog_link ); ?>"
        >
          Смотреть растения
        </a>
      </div>
    </section>

  </main>
</div>

<?php
get_template_part( 'template-parts/popups/service-popup', null, ['type' => 'dizajneram'] );
get_footer();
?>
