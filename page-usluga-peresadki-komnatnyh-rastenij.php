<?php get_header(); ?>

<div class="content-area">
  <main id="main" class="site-main page-ukhod page-peresadka" role="main">

    <?php
    $service_hero_title = get_field( 'service_hero_title' ) ?: "Пересадка комнатных\nрастений";

    $service_hero_intro = get_field( 'service_hero_intro' ) ?: 'Подберём подходящий горшок и грунт, аккуратно пересадим растение и дадим рекомендации по дальнейшему уходу. Без грязи, лишних покупок и риска повредить корни.';

    $service_hero_bottom_text = get_field( 'service_hero_bottom_text' ) ?: 'Привезите растение к нам или закажите выезд специалиста домой, в офис или коттедж.';

    $service_hero_bottom_accent = get_field( 'service_hero_bottom_accent' ) ?: 'Подготовим всё необходимое и сделаем пересадку аккуратно.';

    $service_hero_image_desktop = get_field( 'service_hero_image_desktop' );
    $service_hero_desktop_url = ! empty( $service_hero_image_desktop['url'] ) ? $service_hero_image_desktop['url'] : get_template_directory_uri() . '/images/frontend/ukhod-hero-desktop.png';

    $service_hero_image_mobile = get_field( 'service_hero_image_mobile' );
    $service_hero_mobile_url = ! empty( $service_hero_image_mobile['url'] ) ? $service_hero_image_mobile['url'] : get_template_directory_uri() . '/images/frontend/ukhod-hero-mob.png';

    $service_hero_image_alt = get_field( 'service_hero_image_alt' );
    $service_hero_image_alt = ! empty( $service_hero_image_desktop['alt'] ) ? $service_hero_image_desktop['alt'] : $service_hero_image_alt;
    $service_hero_image_alt = $service_hero_image_alt ?: 'Профессиональный уход за растениями';
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
            <button class="button button--green page-popup-open-btn" name="Оставить заявку на пересадку с первого экрана">
              Оставить заявку
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

    <section class="section page-ukhod__services page-ukhod__stages d-none">
      <div class="page-ukhod__stages-col">
        <h2 class="h2 page-ukhod__services-title">Как проходит пересадка</h2>

        <img
          class="page-ukhod__stages-image page-peresadka__stages-image mobile-hidden"
          loading="lazy"
          src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod.png' ); ?>"
          alt="Пересадка растения специалистом"
        >
      </div>

      <ul class="page-ukhod__services-list">
        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">01</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Оцениваем растение</h3>
            <p class="page-ukhod__services-item-descr">Осматриваем состояние растения и корневой системы, уточняем условия содержания и причину пересадки.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">02</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Подбираем горшок</h3>
            <p class="page-ukhod__services-item-descr">Помогаем выбрать подходящий диаметр, форму и тип горшка или кашпо с учётом размера растения.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">03</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Готовим грунт и дренаж</h3>
            <p class="page-ukhod__services-item-descr">Составляем подходящую почвенную смесь и подбираем дренаж с учётом потребностей конкретного вида.</p>
          </div>
        </li>

        <img
          class="page-ukhod__stages-image page-peresadka__stages-image desktop-hidden"
          loading="lazy"
          src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod.png' ); ?>"
          alt="Пересадка растения специалистом"
        >

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">04</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Аккуратно пересаживаем</h3>
            <p class="page-ukhod__services-item-descr">Освобождаем корни от лишнего субстрата, удаляем повреждённые части при необходимости и размещаем растение в новом горшке.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">05</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Даём рекомендации</h3>
            <p class="page-ukhod__services-item-descr">Объясняем, когда поливать растение после пересадки и как ухаживать за ним в период адаптации.</p>
          </div>
        </li>
      </ul>
    </section>

    <section class="section page-ukhod__services page-ukhod__included">
      <h2 class="h2 page-ukhod__services-title">Что входит в услугу</h2>

      <ul class="page-ukhod__included-list">
        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-2' ); ?>
          </span>
          <p class="page-ukhod__included-text">Осмотр растения и корневой системы</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-8' ); ?>
          </span>
          <p class="page-ukhod__included-text">Подбор подходящего размера горшка</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-5' ); ?>
          </span>
          <p class="page-ukhod__included-text">Подготовка грунта и дренажа</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-4' ); ?>
          </span>
          <p class="page-ukhod__included-text">Удаление повреждённых корней при необходимости</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-7' ); ?>
          </span>
          <p class="page-ukhod__included-text">Аккуратная пересадка без повреждения растения</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-6' ); ?>
          </span>
          <p class="page-ukhod__included-text">Консультация по уходу после пересадки</p>
        </li>
      </ul>
    </section>

    <section class="section page-peresadka__notice">
      <div class="page-peresadka__notice-icon">
        <?php echo plnt_icon( 'leaf' ); ?>
      </div>

      <div class="page-peresadka__notice-content">
        <h2 class="h2 page-peresadka__notice-title">Можно ли пересаживать растение сразу после покупки?</h2>
        <p>
          Да, во многих случаях это допустимо и даже желательно. Большинство комнатных растений продаётся во временных транспортировочных горшках. Специалист оценит состояние растения и выберет безопасный способ пересадки с учётом сезона, вида и состояния корней.
        </p>
      </div>
    </section>

    <section class="section page-ukhod__tariffs">
      <h2 class="h2 page-ukhod__tariffs-title">Варианты пересадки</h2>

      <div class="page-ukhod__tariffs-items">
        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">Бесплатно</span>
              <span class="page-ukhod__tariffs-item-period">в нашей мастерской</span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">При покупке растения и горшка</h3>

            <p class="page-ukhod__tariffs-item-descr">
              Бесплатно пересадим растение при одновременной покупке растения и подходящего горшка в нашем интернет-магазине. Поможем выбрать размер, подготовим грунт и дренаж — растение приедет к вам полностью готовым.
            </p>

            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Заказать бесплатную пересадку"
            >
              Заказать
            </button>
          </div>
        </div>

        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">от 200 ₽</span>
              <span class="page-ukhod__tariffs-item-period">в ваш горшок</span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">Пересадка в ваш горшок или кашпо</h3>

            <p class="page-ukhod__tariffs-item-descr">
              Можно привезти горшок, купленный в другом магазине, или заказать его на маркетплейсе. Подскажем подходящий размер, при необходимости заберём заказ из ближайшего пункта выдачи и пересадим растение в нашей мастерской.
            </p>

            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Заказать пересадку в свой горшок"
            >
              Заказать
            </button>
          </div>
        </div>

        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">от 3 000 ₽/выезд</span>
              <span class="page-ukhod__tariffs-item-period">дом, офис, коттедж</span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">Пересадка с выездом биолога</h3>

            <p class="page-ukhod__tariffs-item-descr">
              Специалист приедет к вам, привезёт необходимые материалы, пересадит растения и проконсультирует по уходу. Подходит для крупных растений, большого количества растений и случаев, когда перевозка неудобна.
            </p>

            <div class="page-ukhod__tariffs-button-wrap">
              <button
                class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
                name="Заказать пересадку с выездом"
              >
                Заказать
              </button>
              <span class="page-ukhod__tariffs-item-info mobile-hidden">
                <?php echo plnt_icon( 'warning' ); ?>
              </span>
            </div>
          </div>

          <div class="page-ukhod__tariffs-item-comment">
            <p>Стоимость пересадки рассчитывается отдельно и зависит от диаметра нового горшка.</p>
            <p>Выезд дальше 10 км от МКАД рассчитывается индивидуально.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section page-peresadka__prices">
      <h2 class="h2 page-peresadka__prices-title">Стоимость пересадки</h2>

      <div class="page-peresadka__prices-grid">
        <div class="page-peresadka__price-card">
          <h3 class="h4 page-peresadka__price-card-title">Пересадка в ваш горшок</h3>
          <p class="page-peresadka__price-card-intro">Цена зависит от диаметра нового горшка или кашпо.</p>

          <div class="page-peresadka__table-wrap">
            <table class="page-peresadka__table">
              <thead>
                <tr>
                  <th>Диаметр</th>
                  <th>Стоимость</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>до 12 см</td>
                  <td>200 ₽</td>
                </tr>
                <tr>
                  <td>от 13 до 15 см</td>
                  <td>400 ₽</td>
                </tr>
                <tr>
                  <td>от 16 до 21 см</td>
                  <td>600 ₽</td>
                </tr>
                <tr>
                  <td>от 22 до 28 см</td>
                  <td>1 000 ₽</td>
                </tr>
                <tr>
                  <td>от 29 до 40 см</td>
                  <td>2 000 ₽</td>
                </tr>
              </tbody>
            </table>
          </div>

          <p class="page-peresadka__price-note">В стоимость включены работа, грунт и дренаж.</p>
        </div>

        <div class="page-peresadka__price-card">
          <h3 class="h4 page-peresadka__price-card-title">Выезд биолога</h3>
          <p class="page-peresadka__price-card-intro">Стоимость выезда добавляется к стоимости пересадки растений.</p>

          <div class="page-peresadka__table-wrap">
            <table class="page-peresadka__table">
              <thead>
                <tr>
                  <th>Зона выезда</th>
                  <th>Стоимость</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Внутри МКАД</td>
                  <td>3 000 ₽</td>
                </tr>
                <tr>
                  <td>До 5 км от МКАД</td>
                  <td>4 000 ₽</td>
                </tr>
                <tr>
                  <td>Дальше 5 км от МКАД</td>
                  <td>Индивидуально</td>
                </tr>
              </tbody>
            </table>
          </div>

          <p class="page-peresadka__price-note">Точная стоимость подтверждается после уточнения адреса и объёма работ.</p>
        </div>
      </div>
    </section>

    <section class="section page-peresadka__example">
      <div class="page-peresadka__example-content">
        <h2 class="h2 page-peresadka__example-title">Пример расчёта пересадки с выездом</h2>
        <p>
          Два фикуса пересаживаются в кашпо диаметром 40 см, а хлорофитум — в горшок диаметром 25 см. Адрес находится внутри МКАД.
        </p>
      </div>

      <div class="page-peresadka__example-card">
        <div class="page-peresadka__table-wrap">
          <table class="page-peresadka__table page-peresadka__table--example">
            <tbody>
              <tr>
                <td>Выезд биолога внутри МКАД</td>
                <td>3 000 ₽</td>
              </tr>
              <tr>
                <td>Пересадка двух растений в кашпо 29–40 см</td>
                <td>4 000 ₽</td>
              </tr>
              <tr>
                <td>Пересадка растения в горшок 22–28 см</td>
                <td>1 000 ₽</td>
              </tr>
              <tr class="page-peresadka__table-total">
                <td>Итого</td>
                <td>8 000 ₽</td>
              </tr>
            </tbody>
          </table>
        </div>

        <button
          class="button button--green page-popup-open-btn"
          name="Оставить заявку из примера расчёта"
        >
          Оставить заявку
        </button>
      </div>
    </section>

    <section class="section page-ukhod__advantages">
      <h2 class="h2 page-ukhod__advantages-title">Почему пересадку стоит доверить нам?</h2>

      <div class="advantages">
        <div class="advantages__wrap">
          <?php echo plnt_icon( 'leaf' ); ?>
          <h3 class="h5 advantages__title">Учитываем особенности растения</h3>
          <p class="advantages__descr">Подбираем состав грунта, размер горшка и способ пересадки для конкретного вида.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'verify' ); ?>
          <h3 class="h5 advantages__title">Работаем аккуратно</h3>
          <p class="advantages__descr">Бережно обращаемся с корнями и не травмируем растение без необходимости.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'home' ); ?>
          <h3 class="h5 advantages__title">Не оставляем грязи</h3>
          <p class="advantages__descr">Подготавливаем рабочее место и убираем всё после пересадки.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'buildings' ); ?>
          <h3 class="h5 advantages__title">Работаем с любыми объёмами</h3>
          <p class="advantages__descr">Пересаживаем одно домашнее растение или озеленение целого офиса.</p>
        </div>
      </div>
    </section>

  </main>
</div>

<?php
get_template_part( 'template-parts/popups/service-popup', null, ['type' => 'peresadka'] );
get_footer();
?>
