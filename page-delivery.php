<?php
get_header(); ?>

<?php 
    global $delivery_inMKAD;
    global $delivery_outMKAD;
   
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    $min_medium_delivery = carbon_get_theme_option('min_medium_delivery');
    $min_free_delivery = carbon_get_theme_option('min_free_delivery');

    $large_markup_delivery_in_mkad = carbon_get_theme_option('large_markup_delivery_in_mkad');
    $large_markup_delivery_out_mkad = carbon_get_theme_option('large_markup_delivery_out_mkad');

    $small_markup_delivery = carbon_get_theme_option('small_markup_delivery');
    $medium_markup_delivery = carbon_get_theme_option('medium_markup_delivery');

    $urgent_markup_delivery = carbon_get_theme_option('urgent_markup_delivery');
    $urgent_markup_delivery_large = carbon_get_theme_option('urgent_markup_delivery_large');

    $expensive_interval_markup_delivery = carbon_get_theme_option('expensive_interval_markup_delivery');
    $expensive_interval_delivery = carbon_get_theme_option('expensive_interval_delivery');

    $isUrgentCourierTariff = carbon_get_theme_option('is_urgent_courier_tariff') == '1';
    $isHolidayCourierTariff = carbon_get_theme_option('is_holiday_courier_tariff') == '1';

    $shipping_costs = plnt_get_shiping_costs();

    $in_mkad = $shipping_costs[$delivery_inMKAD];
    $out_mkad = $shipping_costs[$delivery_outMKAD];
    $min_small_delivery_minus_1 =  floatval(str_replace(' ', '',  $min_small_delivery)) - 1;
    $min_medium_delivery_minus_1 =  floatval(str_replace(' ', '',  $min_medium_delivery)) - 1;


    $intervals = [
      '11:00 - 22:00',
      '11:00 - 16:00',
      '14:00 - 19:00',
      '18:00 - 22:00',
    ];

?>

<div class="content-area content-area_sidebar">
    <aside class='info-menu-sidebar'>  
        <?php get_template_part('template-parts/info-pages-list');?> 
    </aside> 
	<main id="main" class="site-main" role="main">
    <div class="catalog__header-inner">
      <header class="entry-header">
            <h1 class="entry-title">Доставка и самовывоз</h1>   
          </header>
      <div class="catalog__header-image-wrap darken">
        <img
        class="catalog__header-image"
        src="<?php echo esc_url( get_template_directory_uri() . '/images/frontend/delivery-header-img.png' ); ?>" 
        alt=""
        width="180"
        height="140">
      </div>
    </div> 

    <section>
      <h2 class="h3">Доставка</h2>
      <div class="delivery__block delivery-table">
        <div class="delivery-table__head">
          <div class="delivery-table__cell">Расстояние</div>
          <div class="delivery-table__cell">Доставка</div>
          <div class="delivery-table__cell">Крупногабаритная доставка</div>
        </div>
        <div class="delivery-table__section delivery-table__section--accent">
          <div class="delivery-table__section-title">
            Доставка на следующий день или позже
          </div>
          <div class="delivery-table__row">
            <div class="delivery-table__cell">В пределах МКАД</div>
            <div class="delivery-table__cell">от <?php echo $in_mkad; ?> ₽</div>
            <div class="delivery-table__cell">
              от <?php echo floatval(str_replace(' ', '', $in_mkad)) + floatval(str_replace(' ', '', $large_markup_delivery_in_mkad)); ?> ₽
            </div>
          </div>
          <div class="delivery-table__row">
            <div class="delivery-table__cell">За пределы МКАД (до 5 км)</div>
            <div class="delivery-table__cell">от <?php echo $out_mkad; ?> ₽</div>
            <div class="delivery-table__cell">
              от <?php echo floatval(str_replace(' ', '', $out_mkad)) + floatval(str_replace(' ', '', $large_markup_delivery_out_mkad)); ?> ₽
            </div>
          </div>
          <div class="delivery-table__row">
            <div class="delivery-table__cell">За пределы МКАД (от 5 км)</div>
            <div class="delivery-table__cell delivery-table__cell--wide">
              по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа
            </div>
          </div>
        </div>
        <div class="delivery-table__section">
          <div class="delivery-table__section-title">
            Срочная «день в день». Можно оформить до 18:00
          </div>
          <?php if ( $isUrgentCourierTariff ) : ?>
            <div class="delivery-table__row">
              <div class="delivery-table__cell">Все зоны</div>
              <div class="delivery-table__cell delivery-table__cell--wide">
                осуществляется по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа
              </div>
            </div>
          <?php else : ?>
            <div class="delivery-table__row">
              <div class="delivery-table__cell">В пределах МКАД</div>
              <div class="delivery-table__cell">
                от <?php echo floatval(str_replace(' ', '', $in_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery)); ?> ₽
              </div>
              <div class="delivery-table__cell">
                от <?php echo floatval(str_replace(' ', '', $in_mkad)) + floatval(str_replace(' ', '', $large_markup_delivery_in_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery_large)); ?> ₽
              </div>
            </div>
            <div class="delivery-table__row">
              <div class="delivery-table__cell">За пределы МКАД (до 5 км)</div>
              <div class="delivery-table__cell">
                от <?php echo floatval(str_replace(' ', '', $out_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery)); ?> ₽
              </div>
              <div class="delivery-table__cell">
                от <?php echo floatval(str_replace(' ', '', $out_mkad)) + floatval(str_replace(' ', '', $large_markup_delivery_out_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery_large)); ?> ₽
              </div>
            </div>
            <div class="delivery-table__row">
              <div class="delivery-table__cell">За пределы МКАД (от 5 км)</div>
              <div class="delivery-table__cell delivery-table__cell--wide">
                по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="delivery__block delivery-note">
        <?php if ( $isHolidayCourierTariff ) : ?>
          <p>
            В связи с высокой загрузкой курьеров в праздничные дни заказы стоимостью до 5000 руб доставляются по тарифу курьерской службы.
          </p>
        <?php endif; ?>
      
        <?php if ( $min_small_delivery || $min_medium_delivery ) : ?>
          <?php if ( $min_small_delivery ) : ?>
            <p>
              Цена доставки для заказов стоимостью до <?php echo $min_small_delivery_minus_1; ?> рублей увеличена
              <b>на <?php echo $small_markup_delivery; ?> ₽</b>
            </p>
          <?php endif; ?>
      
          <?php if ( $min_medium_delivery ) : ?>
            <p>
              Цена доставки для заказов стоимостью от <?php echo $min_small_delivery; ?> до <?php echo $min_medium_delivery_minus_1; ?> рублей увеличена
              <b>на <?php echo $medium_markup_delivery; ?> ₽</b>
            </p>
          <?php endif; ?>
        <?php endif; ?>
      
        <p class="info__note">В итоговой стоимости заказа не учитывается цена доставки!</p>
        <p>
          Доставка крупномерных растений (от 100см), больших заказов, высоких или тяжелых кашпо осуществляется грузовым автомобилем.
        </p>
      </div>

 
      <div class="delivery__block delivery-note">
  
        <h3 class="h4">Интервалы доставки</h3>

        <p>Мы работаем без выходных, поэтому <strong>доставка осуществляется каждый день.</strong></p>
        <ul class="delivery__intervals">
          <?php foreach ($intervals as $interval): ?>
            <li>с <?= str_replace(' - ', ' до ', $interval); ?><?= ($interval == $expensive_interval_delivery)
                ? '<span> + ' . (int)$expensive_interval_markup_delivery . ' рублей к стоимости доставки</span>'
                : '' ?></li>
          <?php endforeach; ?>
        </ul>
    
        <p>При оформлении срочной доставки “день в день” менеджер согласует с вами удобный интервал доставки.</p>					
        
      </div>
    </section>

    <section>
      <div class="delivery__block delivery-pickup">
        <div class="delivery-pickup__content">
          <h2 class="h3">Самовывоз</h2>

          <div class="delivery-pickup__text">
            <div class="delivery-pickup__text-inner">
              <p>Вы можете бесплатно забрать товары из нашего шоурума</p>
              <p class="delivery-pickup__adress">
                г. Москва, ул. Мещерякова, д.3 (от м. Тушинская или м. Сокол).
              </p>
            </div>

            <div class="delivery-pickup__text-inner">
              <p>Ежедневно</p>
              <p class="delivery-pickup__time">
                10:00-20:00
              </p>
            </div>
          </div>
        </div>

        <div class="delivery-pickup__map">
          <iframe
            src="https://yandex.ru/map-widget/v1/?z=12&ol=biz&oid=237252555639"
            width="560"
            height="400"
            frameborder="0"
            loading="lazy"
          ></iframe>
        </div>
      </div>
    </section>

	</main>  
</div>



<?php get_footer(); ?>