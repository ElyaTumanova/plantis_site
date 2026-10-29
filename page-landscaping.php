<?php get_header(); ?>

<div class="content-area">
  <main id="main" class="site-main page-ukhod page-landscaping" role="main">

    <?php
    $service_hero_title = get_field( 'service_hero_title' ) ?: "Озеленение\nофисов";

    $service_hero_intro = get_field( 'service_hero_intro' ) ?: 'Подберём растения и кашпо под особенности вашего пространства, разработаем концепцию и реализуем проект под ключ — аккуратно, профессионально и в согласованные сроки.';

    $service_hero_bottom_text = get_field( 'service_hero_bottom_text' ) ?: 'В Plantis мы создаём индивидуальные решения для офисов любого размера.';

    $service_hero_bottom_accent = get_field( 'service_hero_bottom_accent' ) ?: 'Продумываем зелёное пространство от первой идеи до установки растений.';

    $service_hero_image_desktop = get_field( 'service_hero_image_desktop' );
    $service_hero_desktop_url = ! empty( $service_hero_image_desktop['url'] ) ? $service_hero_image_desktop['url'] : get_template_directory_uri() . '/images/frontend/ukhod-hero-desktop.png';

    $service_hero_image_mobile = get_field( 'service_hero_image_mobile' );
    $service_hero_mobile_url = ! empty( $service_hero_image_mobile['url'] ) ? $service_hero_image_mobile['url'] : get_template_directory_uri() . '/images/frontend/ukhod-hero-mob.png';

    $service_hero_image_alt = get_field( 'service_hero_image_alt' );
    $service_hero_image_alt = ! empty( $service_hero_image_desktop['alt'] ) ? $service_hero_image_desktop['alt'] : $service_hero_image_alt;
    $service_hero_image_alt = $service_hero_image_alt ?: 'Озеленение офисов';
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
            <button class="button button--green page-popup-open-btn" name="Оставить заявку на озеленение с первого экрана">
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

    <section class="section page-ukhod__services page-ukhod__stages">
      <div class="page-ukhod__stages-col">
        <h2 class="h2 page-ukhod__services-title">Как проходит озеленение</h2>

        <img
          class="page-ukhod__stages-image page-landscaping__stages-image mobile-hidden"
          loading="lazy"
          src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod.png' ); ?>"
          alt="Растения в современном офисном пространстве"
        >
      </div>

      <ul class="page-ukhod__services-list">
        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">01</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Заявка</h3>
            <p class="page-ukhod__services-item-descr">Вы рассказываете о пространстве и задаче, а мы уточняем основные пожелания и сразу берём проект в работу.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">02</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Выезд и диагностика</h3>
            <p class="page-ukhod__services-item-descr">Специалист оценивает площадь, освещение, влажность, температуру и особенности будущего размещения растений.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">03</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Согласование проекта</h3>
            <p class="page-ukhod__services-item-descr">Подбираем растения и кашпо, предлагаем концепцию размещения, согласовываем состав проекта и итоговую стоимость.</p>
          </div>
        </li>

        <img
          class="page-ukhod__stages-image page-landscaping__stages-image desktop-hidden"
          loading="lazy"
          src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/ukhod.png' ); ?>"
          alt="Растения в современном офисном пространстве"
        >

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">04</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Договор</h3>
            <p class="page-ukhod__services-item-descr">Фиксируем объём работ, сроки, стоимость и условия оплаты. После подписания и оплаты проект переходит в реализацию.</p>
          </div>
        </li>

        <li class="page-ukhod__services-item">
          <span class="page-ukhod__services-item-number">05</span>

          <div class="page-ukhod__services-item-content">
            <h3 class="page-ukhod__services-item-title h5">Реализация озеленения</h3>
            <p class="page-ukhod__services-item-descr">Доставляем растения и кашпо, выполняем посадку, расставляем композиции и передаём готовое зелёное пространство.</p>
          </div>
        </li>
      </ul>
    </section>

    <section class="section page-ukhod__services page-ukhod__included">
      <h2 class="h2 page-ukhod__services-title">Что входит в проект озеленения</h2>

      <ul class="page-ukhod__included-list">
        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-1' ); ?>
          </span>
          <p class="page-ukhod__included-text">Анализ пространства и условий содержания</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-2' ); ?>
          </span>
          <p class="page-ukhod__included-text">Подбор здоровых растений под условия офиса</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-8' ); ?>
          </span>
          <p class="page-ukhod__included-text">Подбор горшков и кашпо в стилистике интерьера</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'gallery-grad' ); ?>
          </span>
          <p class="page-ukhod__included-text">Концепция размещения зелёных композиций</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'receipt-grad' ); ?>
          </span>
          <p class="page-ukhod__included-text">Фиксированная смета и прозрачные условия</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'car-grad' ); ?>
          </span>
          <p class="page-ukhod__included-text">Доставка растений и кашпо на объект</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-7' ); ?>
          </span>
          <p class="page-ukhod__included-text">Посадка, расстановка и оформление пространства</p>
        </li>

        <li class="page-ukhod__included-item">
          <span class="page-ukhod__included-icon">
            <?php echo plnt_icon( 'service-6' ); ?>
          </span>
          <p class="page-ukhod__included-text">Рекомендации и дальнейшее обслуживание</p>
        </li>
      </ul>
    </section>

    <section class="section page-ukhod__advantages">
      <h2 class="h2 page-ukhod__advantages-title">Зачем нужно озеленение офиса?</h2>

      <div class="advantages">
        <div class="advantages__wrap">
          <?php echo plnt_icon( 'verify' ); ?>
          <h3 class="h5 advantages__title">Престиж и доверие к бренду</h3>
          <p class="advantages__descr">Профессиональное озеленение повышает статус компании в глазах клиентов, кандидатов и гостей.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'leaf' ); ?>
          <h3 class="h5 advantages__title">Здоровый эмоциональный фон</h3>
          <p class="advantages__descr">Зелёные композиции помогают снизить уровень стресса и поддерживают комфортную атмосферу в коллективе.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'home' ); ?>
          <h3 class="h5 advantages__title">Комфортный микроклимат</h3>
          <p class="advantages__descr">Живые растения делают пространство уютнее и помогают поддерживать более комфортную влажность воздуха.</p>
        </div>

        <div class="advantages__wrap">
          <?php echo plnt_icon( 'buildings' ); ?>
          <h3 class="h5 advantages__title">Зонирование пространства</h3>
          <p class="advantages__descr">Растения помогают визуально разделить офис на рабочие, переговорные и зоны отдыха.</p>
        </div>
      </div>
    </section>

    <section class="section page-ukhod__tariffs page-landscaping__tariffs">
      <h2 class="h2 page-ukhod__tariffs-title">Стоимость озеленения под ключ</h2>
      <p class="page-landscaping__tariffs-intro">Точная стоимость зависит от количества и размера растений, выбранных кашпо, площади помещения и сложности проекта. Для предварительной оценки можно ориентироваться на три формата.</p>

      <div class="page-ukhod__tariffs-items">
        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">от 15 000 ₽</span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">Акцент</h3>

            <p class="page-ukhod__tariffs-item-descr">
              От 4 до 7 небольших растений для ресепшена, кабинета, переговорной или столовой. Подойдёт, когда нужно добавить несколько выразительных зелёных акцентов без полного изменения пространства.
            </p>

            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Заказать озеленение Акцент"
            >
              Заказать
            </button>
          </div>
        </div>

        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">от 40 000 ₽</span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">Среда</h3>

            <p class="page-ukhod__tariffs-item-descr">
              Два-три крупных напольных растения для кабинета руководителя или система зелёных акцентов для офиса площадью до 100 м². Формат помогает заметно изменить атмосферу помещения.
            </p>

            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Заказать озеленение Среда"
            >
              Заказать
            </button>
          </div>
        </div>

        <div class="page-ukhod__tariffs-item-wrap">
          <div class="page-ukhod__tariffs-item">
            <div class="page-ukhod__tariffs-item-badges">
              <span class="page-ukhod__tariffs-item-cost">от 100 000 ₽</span>
            </div>

            <h3 class="page-ukhod__tariffs-item-title h4">Экосистема</h3>

            <p class="page-ukhod__tariffs-item-descr">
              Полноценный проект с индивидуальной концепцией и уникальным дизайном для комплексной трансформации помещения. Подбор растений, кашпо и расположения выполняется как единая система.
            </p>

            <button
              class="page-ukhod__tariffs-item-order button button--green page-popup-open-btn"
              name="Заказать озеленение Экосистема"
            >
              Заказать
            </button>
          </div>
        </div>
      </div>
    </section>

    <section class="section page-landscaping__care">
      <div class="page-landscaping__care-content">
        <span class="page-landscaping__care-icon">
          <?php echo plnt_icon( 'leaf' ); ?>
        </span>

        <div>
          <h2 class="h2 page-landscaping__care-title">Профессиональное обслуживание растений</h2>
          <p class="page-landscaping__care-descr">Чтобы растения оставались здоровыми и всегда выглядели аккуратно, после реализации проекта можно оформить регулярное обслуживание специалистами Plantis.</p>
        </div>
      </div>

      <a
        class="button button--green-l page-landscaping__care-button"
        href="<?php echo esc_url( home_url( '/professionalnyj-uhod-za-rasteniyami/' ) ); ?>"
      >
        Посмотреть варианты ухода
      </a>
    </section>

    <section class="section page-landscaping__faq">
      <h2 class="h2 page-landscaping__faq-title">Частые вопросы</h2>

      <div class="faq-items">
        <div class="faq-item">
          <div class="faq-question icon icon--plus">
            <h3 class="h5">Сколько времени занимает озеленение?</h3>
          </div>
          <div class="faq-answer">
            <p>Небольшие проекты можно реализовать за 1–2 дня, но обычно озеленение занимает 7–10 дней. Для сложных проектов может потребоваться больше времени.</p>
          </div>
        </div>

        <div class="faq-item">
          <div class="faq-question icon icon--plus">
            <h3 class="h5">Работаете ли вы с небольшими офисами?</h3>
          </div>
          <div class="faq-answer">
            <p>Да. Мы подбираем решения для любых пространств — от отдельных кабинетов до крупных офисов и open space.</p>
          </div>
        </div>

        <div class="faq-item">
          <div class="faq-question icon icon--plus">
            <h3 class="h5">Можно ли озеленить офис, где мало света?</h3>
          </div>
          <div class="faq-answer">
            <p>Да. В таких случаях мы подбираем теневыносливые растения и учитываем расположение источников естественного и искусственного света.</p>
          </div>
        </div>

        <div class="faq-item">
          <div class="faq-question icon icon--plus">
            <h3 class="h5">Сколько живут растения?</h3>
          </div>
          <div class="faq-answer">
            <p>При подходящих условиях и регулярном уходе растения могут сохранять декоративность годами. Мы подбираем устойчивые виды под конкретное помещение.</p>
          </div>
        </div>

        <div class="faq-item">
          <div class="faq-question icon icon--plus">
            <h3 class="h5">Нужен ли растениям регулярный уход?</h3>
          </div>
          <div class="faq-answer">
            <p>Да. Даже неприхотливым растениям нужны полив, подкормка, обрезка и контроль состояния. При необходимости мы можем полностью взять обслуживание на себя.</p>
          </div>
        </div>

        <div class="faq-item">
          <div class="faq-question icon icon--plus">
            <h3 class="h5">Какие документы вы предоставляете?</h3>
          </div>
          <div class="faq-answer">
            <p>Мы работаем по договору, предоставляем закрывающие документы, необходимые сертификаты соответствия и документы фитосанитарного контроля.</p>
          </div>
        </div>
      </div>
    </section>

  </main>
</div>

<?php
get_template_part( 'template-parts/popups/service-popup', null, ['type' => 'ozelenenie'] );
get_footer();
?>
