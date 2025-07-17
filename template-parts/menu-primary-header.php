<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	$cats_fikus = carbon_get_theme_option('cats_fikus')
?>

<div class="header__main-menu-wrap">
    <div class="container">
        <nav class="header__main-menu-item" data-menu = "menu_item_plants">
            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/">Комнатные растения</a>
        </nav>
        <nav class="header__main-menu-item" data-menu = "menu_item_gorshki">
            <a href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/">Горшки и кашпо</a>
        </nav>
        <nav class="header__main-menu-item" data-menu = "menu_item_ukhod">
            <a href="<?php echo site_url()?>/product-category/ukhod/">Все для ухода</a>
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
                    <ul>
                        <li class="header__main-submenu-item header__main-submenu-item_accent">
                            <a href="<?php echo site_url()?>/product-category/komnatnye-rasteniya/">Комнатные растения</a>
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
                    <ul >
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
                    <ul>
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
                    <ul>
                        <li class="header__main-submenu-item header__main-submenu-item_accent">
                            <a href="<?php echo site_url()?>/product-category/ukhod/">Всё для ухода</a>
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
                    <img src="<?php echo $cats_fikus ?>" class="header__main-submenu-img" alt="Пальмы">
                </div>
                <div class="header__main-submenu" data-menu = "menu_item_gorshki">
                    <ul>
                        <li class="header__main-submenu-item header__main-submenu-item_accent">
                            <a href="<?php echo site_url()?>/product-category/gorshki_i_kashpo/">Керамические горшки</a>
                        </li>
                        <li class="header__main-submenu-item header__main-submenu-item_accent">
                            <a href="<?php echo site_url()?>/product-tag/kashpo-s-avtopolivom/">Кашпо с автополивом</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>	
</div>