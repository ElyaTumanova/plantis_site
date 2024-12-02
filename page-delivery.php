<?php
get_header(); ?>

<?php 
    // стоимость доставки

    $in_mkad = carbon_get_theme_option('in_mkad');
    $out_mkad = carbon_get_theme_option('out_mkad');

    $min_free_delivery = carbon_get_theme_option('min_free_delivery');
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');

    $large_delivery_markup = carbon_get_theme_option('large_delivery_markup');
    $urgent_delivery_markup = carbon_get_theme_option('urgent_delivery_markup');
    $small_delivery_markup = carbon_get_theme_option('small_delivery_markup');


    $in_mkad_large = $in_mkad + $large_delivery_markup;
    $out_mkad_large = $out_mkad + $large_delivery_markup;
    $in_mkad_urg_large = $in_mkad_urg + $large_delivery_markup;
    $out_mkad_urg_large = $out_mkad_urg + $large_delivery_markup;


    $in_mkad_small = $in_mkad + $small_delivery_markup;
    $out_mkad_small = $out_mkad + $small_delivery_markup;
    $in_mkad_small_urg = $in_mkad + $small_delivery_markup + $urgent_delivery_markup;
    $out_mkad_small_urg = $out_mkad + $small_delivery_markup + $urgent_delivery_markup;
    

?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
        <div class="delivery info__list">

            <div class="delivery__block">
                <div class="delivery__header">
                    <h1 class="entry-header">Доставка</h1>
                    <span class="delivery__dropdown-arrow">next</span>
                </div>
                <div class="delivery__dropdown">
                    <div>
                        <h3 class="delivery__heading heading-2">Если ваш заказ <b>от <?php echo $min_small_delivery ?></b> рублей:</h3>
                        <p><strong>Доставка на следующий день или позже:</strong></p>
                            <ul>
                                <li>в пределах МКАД — <?php echo $in_mkad ?> рублей;</li>
                                <li>за пределы МКАД (до 5 км) — <?php echo $out_mkad ?> рублей;</li>
                                <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                            </ul>
                        <p><strong>Срочная “день в день”</strong>. Можно оформить до 18:00:</p>
                        <ul>
                            <li>в пределах МКАД — <?php echo $in_mkad_urg ?> рублей;</li>
                            <li>за пределы МКАД (до 5 км) — <?php echo $out_mkad_urg ?> рублей;</li>
                            <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                        </ul>				
                    </div>
                
                    <div>
                        <h3 class="delivery__heading heading-2">Если ваш заказ <b>до <?php echo $min_small_delivery ?></b> рублей:</h3>
                        <p><strong>Доставка на следующий день или позже:</strong></p>
                        <ul>
                            <li>в пределах МКАД — <?php echo $in_mkad_small ?> рублей;</li>
                            <li>за пределы МКАД (до 5 км) — <?php echo $out_mkad_small ?> рублей;</li>
                            <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                        </ul>
                        <p><strong>Срочная “день в день”</strong>. Можно оформить до 18:00:</p>
                        <ul>
                            <li>в пределах МКАД — <?php echo $in_mkad_small_urg ?> рублей;</li>
                            <li>за пределы МКАД (до 5 км) — <?php echo $out_mkad_small_urg ?> рублей;</li>
                            <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                        </ul>
                        <p class="info__note">В итоговой стоимости заказа не учитывается цена доставки!</p>
                    </div>
                </div>
            </div>
                <?php if($large_delivery_markup) { 
                    echo '<div class="delivery__block">
                        <div class="delivery__header">
                            <h2 class="entry-header">Крупногабаритная доставка</h2>
                            <span class="delivery__dropdown-arrow">next</span>
                        </div>
                        <div class="delivery__dropdown">
                            <p>Доставка крупномерных растений (от 100см), больших заказов, высоких или тяжелых кашпо осуществляется грузовым автомобилем.</p>
                            <p><strong>Крупногабаритная доставка на следующий день или позже:</strong></p>
                            <ul>
                                <li>в пределах МКАД — '.$in_mkad_large.' рублей;</li>
                                <li>за пределы МКАД (до 5 км) — '.$out_mkad_large.' рублей;</li>
                                <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                            </ul>
                            <p><strong>Крупногабаритная срочная “день в день”. Можно оформить до 18:00:</strong></p>
                            <ul>
                                <li>в пределах МКАД — '.$in_mkad_urg_large.' рублей;</li>
                                <li>за пределы МКАД (до 5 км) — '.$out_mkad_urg_large.' рублей;</li>
                                <li>за пределы МКАД (от 5 км) — по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                            </ul>
                        </div>
                    </div>';
                }?>				
                    
                <?php if($min_free_delivery) { echo '<p>При заказе товаров на сумму <strong>свыше '.$min_free_delivery.' рублей</strong> доставка осуществляется бесплатно.</p>';}?>				
                
                <div class="delivery__block">
                    <div class="delivery__header">
                        <h2 class="entry-header">Самовывоз</h2>
                        <span class="delivery__dropdown-arrow">next</span>
                    </div>
                    <div class="delivery__dropdown">
                        <h3 class="delivery__heading heading-2">Где</h3>
                        <p>Вы можете бесплатно забрать товары из нашего шоурума по адресу г. Москва, ул. Мещерякова, д.3 (от м. Тушинская или м. Сокол).</p>
                        <div class="info__map">
                            <iframe src="https://yandex.ru/map-widget/v1/?z=12&ol=biz&oid=237252555639" width="560" height="400" frameborder="0"></iframe>
                        </div>

                        <h3 class="delivery__heading heading-2">Когда</h3>
                            <p>Мы ждем Вас ежедневно в рабочие часы.</p>
                            <p>Необходимо предварительно связаться с нами и договорится о времени Вашего прибытия.</p>
                    </div>
                </div>
                
                
                <div class="delivery__block">
                    <div class="delivery__header">
                        <h3 class="entry-header">Интервалы доставки</h3>
                        <span class="delivery__dropdown-arrow">next</span>
                    </div>
                    <div class="delivery__dropdown">
                        <ul>
                            <li>с 11:00 до 21:00;</li>
                            <li>с 11:00 до 16:00;</li>
                            <li>с 14:00 до 18:00;</li>
                            <li>с 18:00 до 21:00.</li>
                        </ul>
                        <p>Мы работаем без выходных, поэтому <strong>доставка осуществляется каждый день.</strong></p>
                        <p>При оформлении срочной доставки “день в день” менеджер согласует с вами удобный интервал доставки.</p>					
                    </div>
                </div>

                <div class="delivery__block">
                    <div class="delivery__header">
                        <h3 class="entry-header">Связаться с нами</h3>
                        <span class="delivery__dropdown-arrow">next</span>
                    </div>
                    <div class="delivery__dropdown">
                        <p>Ничего страшного, если вы не можете принять заказ в согласованные дату и время. В таком случае просим связаться с нами удобным для вас способом.</p>

                        <?php get_template_part('template-parts/contacts-part');?>
                    </div>
                </div>


           

		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>