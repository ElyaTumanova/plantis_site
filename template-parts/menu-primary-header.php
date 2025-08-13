<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$arrow_icon = carbon_get_theme_option('arrow_icon');
?>
<?php $start = microtime(true); ?>
<div class="header__main-menu-wrap">
    <div class="container">
        <div class="header__main-menu-item" data-menu = "menu_item_plants">
            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/">Комнатные растения</a>
        </div>
        <div class="header__main-menu-item" data-menu = "menu_item_gorshki">
            <a href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/">Горшки и кашпо</a>
        </div>
        <div class="header__main-menu-item" data-menu = "menu_item_treez_plants">
            <a href="<?php echo site_url()?>/product-category/iskusstvennye-rasteniya-treez/">Искусственные растения Treez</a>
        </div>
        <div class="header__main-menu-item" data-menu = "menu_item_service">Услуги</div>
        <div class="header__main-menu-item">
            <a href="<?php echo site_url()?>/product-tag/skidki/">Скидки</a>
        </div>
        <!-- <div class="header__main-menu-item">
            <a href="<?php //echo site_url()?>/#/">Подарочный сертификат</a>
        </div> -->
    </div>
    <div class="header__menu"> 
        <div class="container">
            <nav id="site-navigation" class="main-navigation" role="navigation">
                <div class="header__main-submenu" data-menu = "menu_item_plants">
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item">
                            <a class="header__main-submenu-item_accent header__main-submenu-item_link header__main-submenu-item_image" 
                            data-cat_id = <?php 
                            $category = get_term_by( 'slug', 'komnatnye-rasteniya', 'product_cat' );
                            echo $category;
                            $id = $category->term_id;
                            echo $id?> 
                            href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/">
                                Комнатные растения
                                <?php echo $arrow_icon?>
                            </a> 
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/dekorativno-listvennye">Декоративно-лиственные</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/fikusy">Фикусы</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/dekorativno-cvetushchie">Цветущие</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/palms">Пальмы</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/lianas">Лианы</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/citrusovye">Цитрусовые</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/paporotniki">Папоротники</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/succulent">Суккуленты</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/kaktusy">Кактусы</a>
                        </li>
                    </ul>
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item header__main-submenu-item_accent">
                            Популярные подборки
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/napolnye/">Напольные</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/novichkam/">Неприхотливые</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/pet-friendly/">Pet Friendly</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/malenkie-cvety-v-gorshkah/">Компактные</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/ampelnye/">Ампельные</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/ehkzoticheskie-komnatnye-rasteniya/">Экзотические</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/variegatnye/">Вариегатные</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/bonsay/">Бонсаи</a>
                        </li>
                    </ul>
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item header__main-submenu-item_accent">
                            Повод для подарка
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/">В подарок женщине</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/">В подарок мужчине</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/komnatnoe-rastenie-v-podarok-na-yubilej/">На юбилей</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/komnatnoe-rastenie-v-podarok-nachalniku/">Начальнику</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/komnatnoe-rastenie-v-podarok-na-1-sentyabrya/">День знаний</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/komnatnoe-rastenie-v-podarok-na-den-uchitelya/">День учителя</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/komnatnoe-rastenie-v-podarok-na-den-materi/">День матери</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/komnatnoe-rastenie-v-podarok-na-novyj-god/">Новый год</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/komnatnoe-rastenie-v-podarok-na-den-svyatogo-valentina/">День святого Валентина</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-tag/komnatnoe-rastenie-v-podarok-na-8-marta/">8 марта</a>
                        </li>
                    </ul>
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item">
                            <a class="header__main-submenu-item_accent header__main-submenu-item_link" href="<?php echo site_url()?>/product-category/ukhod/">
                                Всё для ухода
                                <?php echo $arrow_icon?>
                            </a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/ukhod/grunt">Грунт</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/ukhod/udobreniya">Удобрения</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/ukhod/zashchita-rastenij">Защита растений</a>
                        </li>
                    </ul>
                    <img class="header__main-submenu-img" loading="lazy" src="<?php echo get_template_directory_uri()?>/images/interior.webp" alt="Plantis.shop">
                </div>
                <div class="header__main-submenu" data-menu = "menu_item_gorshki">
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item">
                            <a class="header__main-submenu-item_accent header__main-submenu-item_link" href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/">
                                Керамические горшки 
                                <?php echo $arrow_icon?>
                            </a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a class="header__main-submenu-item_accent header__main-submenu-item_link" href="<?php echo site_url()?>/product-tag/kashpo-s-avtopolivom/">
                                Кашпо с автополивом
                                <?php echo $arrow_icon?>
                            </a>
                        </li>
                        <li class="header__main-submenu-item">
                            <p class="header__main-submenu-item_accent">По диаметру</p>
                            <a href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/malenkie-do-17sm">Маленькие (До 17см)</a>
                            <a href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/srednie-ot-18-do-25sm">Средние (От 18 до 25см)</a>
                            <a href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/bolshie-ot-26sm">Большие (От 26см)</a>
                         <li class="header__main-submenu-item">
                            <p class="header__main-submenu-item_accent">Популярные цвета</p>
                            <div class="header__main-submenu-item_row">
                                <a href="<?php echo site_url()?>/attribute/color/belyj/">Белый</a>
                                <a href="<?php echo site_url()?>/attribute/color/chyornyj/">Чёрный</a>
                                <a href="<?php echo site_url()?>/attribute/color/bezhevyy/">Бежевый</a>
                                <a href="<?php echo site_url()?>/attribute/color/seryj/">Серый</a>
                                <a href="<?php echo site_url()?>/attribute/color/zolotoj/">Золотой</a>
                                <a href="<?php echo site_url()?>/attribute/color/serebro/">Серебро</a>
                                <a href="<?php echo site_url()?>/attribute/color/terrakotovyj/">Терракотовый</a>
                            </div>
                        </li>
                        <!-- <li class="header__main-submenu-item header__main-submenu-item_accent">
                            По назначению
                        </li>                       -->
                    </ul>
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item">
                            <a class="header__main-submenu-item_accent header__main-submenu-item_link" href="<?php echo site_url()?>/product-category/kashpo-treez">
                                Кашпо Treez
                                <?php echo $arrow_icon?>
                            </a>
                        </li>
                        <li class="header__main-submenu-item">
                            <?php
                                get_primary_submenu('treez-effectory','/product-category/kashpo-treez/',['Treez Effectory ']);
                            ?>
                        </li>
                        <li class="header__main-submenu-item">
                            <?php
                                get_primary_submenu('treez-ergo','/product-category/kashpo-treez/', ['Treez Ergo ']);
                            ?>
                        </li>
                    </ul>
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item">
                            <?php
                                get_primary_submenu('kashpo-lechuza','/product-category/kashpo-lechuza/',['Кашпо Lechuza ']);
                            ?>
                        </li>
                    </ul>
                    <img class="header__main-submenu-img" loading="lazy" src="<?php echo get_template_directory_uri()?>/images/interior.webp" alt="Plantis.shop">
                </div>
                <div class="header__main-submenu" data-menu = "menu_item_treez_plants">
                    <?php 
                        global $plants_treez_cat_id;
                        $args = array( 'taxonomy' => 'product_cat', 'parent' => $plants_treez_cat_id );  
                        $terms = get_terms( $args ); 
                        foreach($terms as $term) {
                            ?> 
                                <ul class="header__main-submenu_lvl1">
                                <li class="header__main-submenu-item">
                                <?php
                                    $words_to_remove = ['Treez','Искусственные', 'Искусственная', 'Искусственное', 'Искусственный','растения'];
                                    get_primary_submenu($term->slug,'/product-category/iskusstvennye-rasteniya-treez/',$words_to_remove, true);
                                ?>
                            </li>
                            </ul>
                            <?php
                        }
                    ?> 
                    <img class="header__main-submenu-img" loading="lazy" src="<?php echo get_template_directory_uri()?>/images/interior.webp" alt="Plantis.shop">
                </div>
                <div class="header__main-submenu" data-menu = "menu_item_service">
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/usluga-peresadki-komnatnyh-rastenij/">Пересадка</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/landscaping/">Озеленение</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/professionalnyj-uhod-za-rasteniyami/">Профессиональный уход за растениями</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/pokupka-komnatnyh-rastenij-optom/">Оптовая покупка растений</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>

 <?php echo "<!-- Timing: primary menu = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>