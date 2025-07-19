<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php $start = microtime(true); ?>
<div class="header__main-menu-wrap">
    <div class="container">
        <nav class="header__main-menu-item" data-menu = "menu_item_plants">
            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/">Комнатные растения</a>
        </nav>
        <nav class="header__main-menu-item" data-menu = "menu_item_gorshki">
            <a href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/">Горшки и кашпо</a>
        </nav>
        <nav class="header__main-menu-item" data-menu = "menu_item_treez_plants">
            <a href="<?php echo site_url()?>/product-category/iskusstvennye-rasteniya-treez/">Искусственные растения Treez</a>
        </nav>
        <nav class="header__main-menu-item" data-menu = "menu_item_service">Услуги</nav>
        <nav class="header__main-menu-item">
            <a href="<?php echo site_url()?>/product-tag/skidki/">Скидки</a>
        </nav>
        <nav class="header__main-menu-item">
            <a href="<?php echo site_url()?>/#/">Подарочный сертификат</a>
        </nav>
    </div>
    <div class="header__menu"> 
        <div class="container">
            <nav id="site-navigation" class="main-navigation" role="navigation">
                <div class="header__main-submenu" data-menu = "menu_item_plants">
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item">
                            <a class="header__main-submenu-item_accent header__main-submenu-item_link" href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/">Комнатные растения</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/dekorativno-listvennye">Декоративно-лиственные</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/dekorativno-listvennye">Цветущие</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/dekorativno-listvennye">Пальмы</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/dekorativno-listvennye">Лианы</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/dekorativno-listvennye">Цитрусовые</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/dekorativno-listvennye">Папоротники</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/dekorativno-listvennye">Суккуленты</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/dekorativno-listvennye">Кактусы</a>
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
                            <a class="header__main-submenu-item_accent header__main-submenu-item_link" href="<?php echo site_url()?>/product-category/ukhod/">Всё для ухода</a>
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
                    <img class="header__main-submenu-img" loading="lazy" src="https://plantis.shop/wp-content/uploads/2025/06/интерьер.webp" alt="Plantis.shop">
                </div>
                <div class="header__main-submenu" data-menu = "menu_item_gorshki">
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item">
                            <a class="header__main-submenu-item_accent header__main-submenu-item_link" href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/">Керамические горшки</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <a class="header__main-submenu-item_accent header__main-submenu-item_link" href="<?php echo site_url()?>/product-tag/kashpo-s-avtopolivom/">Кашпо с автополивом</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <p class="header__main-submenu-item_accent">По диаметру</p>
                            <a href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/malenkie-do-17sm">Маленькие (До 17см)</a>
                            <a href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/srednie-ot-18-do-25sm">Средние (От 18 до 25см)</a>
                            <a href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/bolshie-ot-26sm">Большие (От 26см)</a>
                        <!-- <li class="header__main-submenu-item header__main-submenu-item_accent">
                            По назначению
                        </li>                       -->
                    </ul>
                    <ul class="header__main-submenu_lvl1">
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
                    </ul>
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item">
                            <a class="header__main-submenu-item_accent header__main-submenu-item_link" href="<?php echo site_url()?>/product-category/kashpo-treez">Кашпо Treez</a>
                        </li>
                        <li class="header__main-submenu-item">
                            <?php
                                get_primary_submenu('treez-effectory','/product-category/kashpo-treez/','Treez Effectory',['Treez Effectory ']);
                            ?>
                        </li>
                        <li class="header__main-submenu-item">
                            <?php
                                get_primary_submenu('treez-ergo','/product-category/kashpo-treez/','Treez Ergo', ['Treez Ergo ']);
                            ?>
                        </li>
                    </ul>
                    <ul class="header__main-submenu_lvl1">
                        <li class="header__main-submenu-item">
                            <?php
                                get_primary_submenu('kashpo-lechuza','/product-category/kashpo-lechuza/','Кашпо Lechuza', ['Кашпо Lechuza ']);
                            ?>
                        </li>
                    </ul>
                    <img class="header__main-submenu-img" loading="lazy" src="https://plantis.shop/wp-content/uploads/2025/06/интерьер.webp" alt="Plantis.shop">
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
                                    get_primary_submenu($term->slug,'/product-category/iskusstvennye-rasteniya-treez/'.$term->slug,$term->name,['Treez','Искусственные', 'Искусственная', 'Искусственное', 'Искусственный']);
                                ?>
                            </li>
                            </ul>
                            <?php
                        }
                    ?> 
                </div>
            </nav>
        </div>
    </div>	
</div>

 <?php echo "<!-- Timing: primary menu = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>