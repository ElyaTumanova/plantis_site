<?php
get_header(); ?>

<?php 
    $in_mkad = carbon_get_theme_option('in_mkad');
    $out_mkad = carbon_get_theme_option('out_mkad');
    $in_mkad_urg = carbon_get_theme_option('in_mkad_urg');
    $out_mkad_urg = carbon_get_theme_option('out_mkad_urg');
    $in_mkad_small = carbon_get_theme_option('in_mkad_small');
    $out_mkad_small = carbon_get_theme_option('out_mkad_small');
    $in_mkad_small_urg = carbon_get_theme_option('in_mkad_small_urg');
    $out_mkad_small_urg = carbon_get_theme_option('out_mkad_small_urg');
    $min_free_delivery = carbon_get_theme_option('min_free_delivery');
    $min_small_delivery = carbon_get_theme_option('min_small_delivery');
    $large_delivery_markup = carbon_get_theme_option('large_delivery_markup');

    $in_mkad_large = $in_mkad + $large_delivery_markup;
    $out_mkad_large = $out_mkad + $large_delivery_markup;
    $in_mkad_urg_large = $in_mkad_urg + $large_delivery_markup;
    $out_mkad_urg_large = $out_mkad_urg + $large_delivery_markup;

?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
        <div class="delivery info__list">
            <div>
                <h1 class="entry-header">Доставка</h1>

                <h3 class="info__heading heading-2">Если ваш заказ <b>от <?php echo $min_small_delivery ?></b> рублей:</h3>
                <div>
                    <p><strong>Доставка на следующий день или позже:</strong></p>
                        <ul>
                            <li>в пределах МКАД — <?php echo $in_mkad ?> рублей;</li>
                            <li>за пределы МКАД (до 5 км) — <?php echo $out_mkad ?> рублей;</li>
                            <li>за пределы МКАД (от 5 км) - по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                        </ul>
                    <p><strong>Срочная “день в день”</strong>. Можно оформить до 18:00:</p>
                    <ul>
                        <li>в пределах МКАД — <?php echo $in_mkad_urg ?> рублей;</li>
                        <li>за пределы МКАД (до 5 км) — <?php echo $out_mkad_urg ?> рублей;</li>
                        <li>за пределы МКАД (от 5 км) - по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                    </ul>				
                </div>
                
                <h3 class="info__heading heading-2">Если ваш заказ <b>до <?php echo $min_small_delivery ?></b> рублей:</h3>
                <div>
				    <p><strong>Доставка на следующий день или позже:</strong></p>
                    <ul>
                        <li>в пределах МКАД — <?php echo $in_mkad_small ?> рублей;</li>
                        <li>за пределы МКАД (до 5 км) — <?php echo $out_mkad_small ?> рублей;</li>
                        <li>за пределы МКАД (от 5 км) - по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                    </ul>
                    <p><strong>Срочная “день в день”</strong>. Можно оформить до 18:00:</p>
                    <ul>
                        <li>в пределах МКАД — <?php echo $in_mkad_small_urg ?> рублей;</li>
                        <li>за пределы МКАД (до 5 км) — <?php echo $out_mkad_small_urg ?> рублей;</li>
                        <li>за пределы МКАД (от 5 км) - по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                    </ul>
                    <p class="info__note">В итоговой стоимости заказа не учитывается цена доставки!</p>

                    <?php if($large_delivery_markup) { 
                        echo '<h3 class="info__heading heading-2">Крупногабаритная доставка</h3>
                        <p>Доставка крупномерных растений (от 100см), больших заказов, высоких или тяжелых кашпо осуществляется грузовым автомобилем.</p>
                        <p><strong>Крупногабаритная доставка на следующий день или позже:</strong></p>
                        <ul>
                            <li>в пределах МКАД — '.$in_mkad_large.' рублей;</li>
                            <li>за пределы МКАД (до 5 км) — '.$out_mkad_large.' рублей;</li>
                            <li>за пределы МКАД (от 5 км) - по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                        </ul>
                        <p><strong>Крупногабаритная срочная “день в день”. Можно оформить до 18:00:</strong></p>
                        <ul>
                            <li>в пределах МКАД — '.$in_mkad_urg_large.' рублей;</li>
                            <li>за пределы МКАД (до 5 км) — '.$out_mkad_urg_large.' рублей;</li>
                            <li>за пределы МКАД (от 5 км) - по тарифу грузоперевозчика, рассчитывается менеджером после оформления заказа.</li>
                        </ul>';
                        }?>				
                    
                    <?php if($min_free_delivery) { echo '<p>При заказе товаров на сумму <strong>свыше '.$min_free_delivery.' рублей</strong> доставка осуществляется бесплатно.</p>';}?>				
                    
                </div>
                
                <h3 class="info__heading heading-2">Интервалы доставки</h3>
                <div>
                    <ul>
                        <li>с 11:00 до 21:00;</li>
                        <li>с 11:00 до 16:00;</li>
                        <li>с 14:00 до 18:00;</li>
                        <li>с 18:00 до 21:00.</li>
                    </ul>
                    <p>Мы работаем без выходных, поэтому <strong>доставка осуществляется каждый день.</strong></p>
                    <p>При оформлении срочной доставки “день в день” менеджер согласует с вами удобный интервал доставки.</p>					
                </div>

                <h3 class="info__heading heading-2">Связаться с нами</h3>
                <p>Ничего страшного, если вы не можете принять заказ в согласованные дату и время. В таком случае просим связаться с нами удобным для вас способом.</p>

                <?php get_template_part('template-parts/contacts-part');?>
            </div>
            <div>
                <h2 class="entry-header">Самовывоз</h2>
                
                <h3 class="info__heading heading-2">Где</h3>
                <p>Вы можете бесплатно забрать товары из нашего шоурума по адресу г. Москва, ул. Мещерякова, д.3 (от м. Тушинская или м. Сокол).</p>
                <div class="info__map">
					<iframe src="https://yandex.ru/map-widget/v1/?z=12&ol=biz&oid=237252555639" width="560" height="400" frameborder="0"></iframe>
				</div>

                <h3 class="info__heading heading-2">Когда</h3>
                    <p>Мы ждем Вас ежедневно в рабочие часы.</p>
                    <p>Необходимо предварительно связаться с нами и договорится о времени Вашего прибытия.</p>
            </div>				
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>