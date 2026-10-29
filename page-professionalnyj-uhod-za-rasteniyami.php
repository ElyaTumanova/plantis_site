<?php get_header();?>

<div class="content-area">
  <main id="main" class="site-main page-ukhod" role="main">

    <?php
    $service_hero_title = get_field( 'service_hero_title' ) ?: "Профессиональный\nуход за растениями";

    $service_hero_intro = get_field( 'service_hero_intro' ) ?: 'Уход за растениями требует знаний и опыта. Попытка сэкономить, поручив его сотрудникам или клинингу, нередко приводит к гибели растений и дополнительным расходам на их замену.';

    $service_hero_bottom_text = get_field( 'service_hero_bottom_text' ) ?: 'Если вы не хотите взваливать на свои плечи и плечи своих сотрудников дополнительную ответственность, то можно делегировать её нам!';

    $service_hero_bottom_accent = get_field( 'service_hero_bottom_accent' ) ?: 'Уход за растениями — наша работа, наше хобби, наша наука.';

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
            <button
              class="button button--green page-popup-open-btn"
              name="Оставить заявку с первого экрана"
            >
              Оставить заявку
            </button>

            <div class="page-ukhod__rasschet-socials-wrap">
              <span>Или напишите нам <br>в&nbsp;мессенджеры</span>
              <?php get_template_part( 'template-parts/social-media-btns' ); ?>
            </div>
          </div>
        </div>

        <picture>
          <source
            media="(max-width: 768px)"
            srcset="<?php echo esc_url( $service_hero_mobile_url ); ?>"
          >
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
        <h2 class="h2 page-ukhod__services-title">Этапы оказания услуги</h2>
        <img
          class="page-ukhod__stages-image mobile-hidden"
          loading="lazy"
          src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod.png' ); ?>"
          alt=""
        >
      </div>
      <ul class="page-ukhod__services-list">
        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">01</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Осмотр растений</h3>
            <p class="page-ukhod__services-item-descr">Наш специалист приезжает к вам, внимательно осматривает растения и оценивает их состояние.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">02</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Согласование условий</h3>
            <p class="page-ukhod__services-item-descr">Обсуждаем задачи, отвечаем на вопросы, подбираем оптимальный формат обслуживания и заключаем договор.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">03</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Составление графика визитов</h3>
            <p class="page-ukhod__services-item-descr">Формируем удобный график регулярного обслуживания, чтобы уход за растениями проходил вовремя и не мешал вашей работе.</p>
          </div>
        </li>

        <img
          class="page-ukhod__stages-image desktop-hidden"
          loading="lazy"
          src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod.png' ); ?>"
          alt=""
        >

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">04</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Индивидуальный план ухода</h3>
            <p class="page-ukhod__services-item-descr">Для каждого растения разрабатываем персональную программу ухода с учётом его вида, возраста, расположения и особенностей содержания.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">05</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">План подкормок и ухода</h3>
            <p class="page-ukhod__services-item-descr">Определяем график внесения удобрений, профилактических обработок, полива и других необходимых процедур для поддержания здоровья растений.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">06</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Ежемесячный отчёт</h3>
            <p class="page-ukhod__services-item-descr">По итогам каждого месяца предоставляем подробный отчёт о выполненных работах, состоянии растений и рекомендациях по дальнейшему уходу.</p>
          </div>
        </li>
      </ul>
    </section>

    <section class="section page-ukhod__services page-ukhod__included">
      <h2 class="h2 page-ukhod__services-title">Что включает в себя обслуживание</h2>

      <ul class="page-ukhod__included-list">
        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-1' ); ?>
          </span>

          <p class="page-ukhod__included-text">
            Оздоровительные<br>
            мероприятия
          </p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-2' ); ?>
          </span>

          <p class="page-ukhod__included-text">
            Подвязка и установка<br>
            подпор
          </p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-3' ); ?>
          </span>

          <p class="page-ukhod__included-text">
            Гигиена растения<br>
            и кашпо
          </p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-4' ); ?>
          </span>

          <p class="page-ukhod__included-text">
            Санитарная и формовочная<br>
            обрезка
          </p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-5' ); ?>
          </span>

          <p class="page-ukhod__included-text">
            Регулярная подкормка растений в зависимости от их вида и времени года
          </p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-6' ); ?>
          </span>

          <p class="page-ukhod__included-text">
            Полив по потребностям каждого растения, опрыскивание
          </p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-7' ); ?>
          </span>

          <p class="page-ukhod__included-text">
            Рыхление земли после полива, замена верхнего слоя грунта при необходимости, досыпание грунта
          </p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-8' ); ?>
          </span>

          <p class="page-ukhod__included-text">
            Плановая пересадка при показаниях для тарифа с гарантийным обслуживанием
          </p>
        </li>
      </ul>
    </section>

    <section class="section page-ukhod__tariffs">
      <h2 class="h2 page-ukhod__tariffs-title">Варианты ухода</h2>
      <div class="page-ukhod__tariffs-items">
        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">от 18 000 ₽/месяц</span>
              <span class="page-ukhod__tariffs-item-period">1 раз в неделю</span>
            </div>
            <h3 class="page-ukhod__tariffs-item-title h4">Стандартное обслуживание растений</h3>
            <p class="page-ukhod__tariffs-item-descr">
              Регулярный уход, который помогает поддерживать растения здоровыми и красивыми. Специалист приезжает раз в неделю, проводит полив, опрыскивание, обрезку, удаляет сухие листья, подкармливает растения и при необходимости обновляет верхний слой грунта. Оптимальный вариант для офисов, кафе и других помещений, где растениям нужен постоянный уход.
            </p>
            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Заказать стандартный тариф"
            >
              Заказать
            </button>
          </div>
        </div>
        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">от 26 000 ₽/месяц</span>
              <span class="page-ukhod__tariffs-item-period">1 раз в неделю</span>
            </div>
            <h3 class="page-ukhod__tariffs-item-title h4">Гарантийное обслуживание растений</h3>
            <p class="page-ukhod__tariffs-item-descr">
              Максимальный уровень заботы о ваших растениях. Помимо регулярного ухода, мы проводим профилактику заболеваний и вредителей, рыхлим и обновляем грунт, устанавливаем опоры, при необходимости пересаживаем растения. Если растение теряет декоративный вид, мы бесплатно заменим его на новое. Услуга доступна для объектов, озеленение которых выполнила наша компания.
            </p>
            <div class="page-ukhod__tariffs-button-wrap">
              <button
                  class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
                  name="Заказать гарантийное обслуживание"
                >
                Заказать
              </button>
               <span class="page-ukhod__tariffs-item-info mobile-hidden">
                <?php echo plnt_icon( 'warning' ); ?>
              </span>
            </div>
          </div>
          <div class="page-ukhod__tariffs-item-comment">
            <p>Данная услуга доступна только в том случае, если озеленение проводили специалисты нашей компании.</p>
            <p>Гарантийное обслуживание можно оформить только до дня поставки растений.</p>
          </div>
        </div>
        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges"><span class="page-ukhod__tariffs-item-cost">от 6 000 ₽/выезд</span></div>
            <h3 class="page-ukhod__tariffs-item-title h4">Разовый выезд биолога</h3>
            <p class="page-ukhod__tariffs-item-descr">
              Комплексный уход за растениями за один визит. Подойдёт для офисов, кафе, салонов, которые хотят привести растения в порядок без регулярного обслуживания. Консультация, полив, обрезка, обработка от вредителей, подкормка — ваши растения будут здоровы и ухожены после одной встречи.
            </p>
            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Заказать разовый выезд"
            >
              Заказать
            </button>
          </div>
        </div>
        
      </div>
    </section>

    <section class="section page-ukhod__advantages">
      <h2 class="h2 page-ukhod__advantages-title">Почему уход за растениями стоит доверить нам?</h2>

      <div class="advantages">
        <div class="advantages__wrap">
          <?php echo plnt_icon( 'leaf' ); ?>
          <h3 class="h5 advantages__title">Заботимся о растениях</h3>
          <p class="advantages__descr">Ваша задача — бизнес. Наша — забота о растениях. Уберём все хлопоты и контроль за уходом возьмём на себя.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'verify' ); ?>
          <h3 class="h5 advantages__title">Поддерживаем имидж</h3>
          <p class="advantages__descr">Ухоженные растения подчёркивают статус и заботу о деталях в вашем бизнесе.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'home' ); ?>
          <h3 class="h5 advantages__title">Создаём уют и атмосферу</h3>
          <p class="advantages__descr">Здоровые растения повышают комфорт и впечатление ваших клиентов и сотрудников.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'buildings' ); ?>
          <h3 class="h5 advantages__title">Работаем с любыми объёмами</h3>
          <p class="advantages__descr">От небольших кафе до крупных офисных центров.</p>
        </div>
      </div>
    </section>

  </main>
</div>

<?php
get_template_part( 'template-parts/popups/service-popup', null, ['type' => 'uhod'] );
get_footer();
?>