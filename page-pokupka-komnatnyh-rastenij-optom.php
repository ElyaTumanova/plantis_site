<?php
get_header(); 

$pricelist_link = carbon_get_theme_option('pricelist_link');
?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
		<header class="entry-header">
			<h1 class="entry-title">ХОТИТЕ КУПИТЬ КОМНАТНЫЕ РАСТЕНИЯ ОПТОМ?</h1>                
		</header>
		<div class="optom">
            <div class="info__content">
                <h2 class="info__accent-text-upper">
                Вы - дизайнер? Или вам просто нужен надежный поставщик комнатных растений?
                А может быть вы - компания, которая самостоятельно решила заняться озеленением своего офиса? 
                </h2>
                <p>Мы уже успели доказать себе и рынку, что умеем делать классный клиентский сервис. Но розничная продажа комнатных растений и оказание сопутствующих услуг – это не всё, что мы умеем.</p>
                <p>Plantis – это дружная команда, которая готова вам помочь в нелегком деле оптовой закупки комнатных растений. Давайте знакомиться!</p>
                
                <h2 class="info__heading heading-2">Про сроки</h2>
                <p>Срок доставки – от 3 до 20 дней, в зависимости от необходимого количества растений и наличия их в питомниках.</p>
    
                <h2 class="info__heading heading-2">Есть ли у нас система скидок для оптовых покупателей?</h2>
                <p>Конечно есть:</p>
    
                <table class="info__table"><tbody><tr><td><b>Сумма заказа</b></td><td><b>Размер скидки</b></td></tr><tr><td>От 50 000 рублей</td><td>5%</td></tr><tr><td>От 100 000 рублей</td><td>10%</td></tr><tr><td>От 200 000 рублей</td><td>15%</td></tr></tbody></table>
                <p>Скидка распространяется только на покупку горшечных растений.</p>
            </div>
            <div class="optom__buttons">
                <a href="<?php 
                global $plants_cat_id;
                echo get_term_link( $plants_cat_id, 'product_cat' );
                ?>" 
                class="optom__button optom__button_cats button">Каталог растений</a>
                <a class="optom__button optom__button_pricelist button" 
                href="<?php echo $pricelist_link;?>">
                Скачать оптовый прайс-лист</a>
                <!-- <button class="optom__button optom__button_pricelist button page-popup-open-btn">Получить оптовый прайс-лист</button> -->
            </div>
            <!-- <h2 class="info__heading heading-2">Категории растений от А до Я</h2>
            <div class="optom__plants-cats-az">
                <div class="plants-cats-az">
                <?php //get_template_part('template-parts/plant-cats-az');
                    // global $plants_cat_id;
                    // $lowest_cats = get_lowest_level_product_categories($plants_cat_id); // начиная с корня
                    // function sortByName($a, $b) {
                    //     return strcmp($a->name, $b->name);
                    // }
                    // usort($lowest_cats, "sortByName");
                    // foreach ( $lowest_cats as $cat ) {
                    //     //print_r($cat);
                    //     echo $cat->name . ' (ID: ' . $cat->term_id . ')<br>';
                    //     $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                    //     $thumbnail_url = wp_get_attachment_url( $thumbnail_id );
                    //     echo($thumbnail_url);
                    // }

                 
                ?>
                </div>
            </div> -->
            <h2 class="info__heading heading-2">Остались вопросы?</h2>
            <p>Позвоните нам или напишите, любым удобным способом!</p>
            <?php get_template_part('template-parts/contacts-part');?>
		</div>
	</main><!-- #main -->
</div><!-- #primary -->
<?php get_template_part('template-parts/popups/pricelist-popup');?>

<?php get_footer(); ?>