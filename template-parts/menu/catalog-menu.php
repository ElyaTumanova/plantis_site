<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php $start = microtime(true); ?>
<nav class="catalog-dropdown container">
  <div class="catalog-dropdown__cats">
    <ul class="catalog-dropdown__cats-list">
      <li class="catalog-dropdown__cats-item icon icon--arrow-right" data-menu="menu_item_plants">
        <?php 
          echo plnt_icon('plants'); 
          plnt_get_menu_link([
            'slug' => 'komnatnye-rasteniya', 
            'classes' => ''
          ]);
        ?>
        <!-- Комнатные растения -->
      </li>

      <li class="catalog-dropdown__cats-item icon icon--arrow-right" data-menu="menu_item_gorshki">
        <?php echo plnt_icon('pots'); 
          plnt_get_menu_link([
            'slug' => 'gorshki_i_kashpo', 
            'classes' => ''
          ]);
        ?>
      </li>

      <li class="catalog-dropdown__cats-item icon icon--arrow-right" data-menu="menu_item_treez_plants">
        <?php echo plnt_icon('treez'); 
          plnt_get_menu_link([
            'slug' => 'iskusstvennye-rasteniya-treez', 
            'classes' => ''
          ]);
        ?>
      </li>
      <li class="catalog-dropdown__cats-item icon icon--arrow-right" data-menu = "menu_az_palnts">
        <?php echo plnt_icon('a-to-z'); ?>
        Растения от А до Я
      </li>

      <li class="catalog-dropdown__cats-item catalog-dropdown__cats-item--mobonly icon icon--arrow-right" data-menu = "menu_gift_card">
        <?php echo plnt_icon('gift'); ?>
        Подарочный сертификат
      </li>

      <li class="catalog-dropdown__cats-item catalog-dropdown__cats-item--mobonly icon icon--arrow-right" data-menu = "menu_services">
        <?php echo plnt_icon('leyka'); ?>
        Услуги
      </li>
      <li class="catalog-dropdown__cats-item catalog-dropdown__cats-item--mobonly">
        <?php echo plnt_icon('percentage-linear'); ?>
        <a class="cats-sub-menu__item-link" href="<?php echo site_url()?>/product-tag/skidki/">Скидки</a>
      </li>
    </ul>
  </div>
  <?php echo "<!-- Timing: dropdown__cats = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>
  <div class="catalog-dropdown__sub-cats">
    <nav class="catalog-dropdown__sub-cats-wrap" data-menu="menu_item_plants">
      <div class="catalog-dropdown__sub-cats-header icon icon--arrow-right h5">
        <?php 
          plnt_get_menu_link([
            'slug' => 'komnatnye-rasteniya', 
            'classes' => 'cats-sub-menu__item-image'
          ]); 
        ?>
      </div>
      <div class="catalog-dropdown__sub-cats-body">
        <div class="catalog-dropdown__sub-cats-inner">
          <?php get_primary_submenu([
            'slug' => 'komnatnye-rasteniya', 
            'show_heading' => false
            ]);?>
               <?php echo "<!-- Timing: submenu komnatnye-rasteniya = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>
            <?php get_primary_submenu([
            'slug' => 'ukhod',
            ]);?>
        </div>
        <?php echo "<!-- Timing: before Популярные подборки = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>
        <div class="catalog-dropdown__sub-cats-inner">
          <?php
            plnt_render_product_tag_menu( 'Популярные подборки', [
              'napolnye'                       => 'Напольные',
              'novichkam'                      => 'Неприхотливые',
              'pet-friendly'                   => 'Pet Friendly',
              'malenkie-cvety-v-gorshkah'      => 'Компактные',
              'ampelnye'                       => 'Ампельные',
              'ehkzoticheskie-komnatnye-rasteniya' => 'Экзотические',
              'variegatnye'                    => 'Вариегатные',
              'bonsay'                         => 'Бонсаи',
            ] );
          ?>
        </div>
        <?php echo "<!-- Timing: after Популярные подборки = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>
        <div class="catalog-dropdown__sub-cats-inner">
          <?php
            plnt_render_product_tag_menu( 'Повод для подарка', [
              'komnatnoe-rastenie-v-podarok-zhenshchine'        => 'В подарок женщине',
              'komnatnoe-rastenie-v-podarok-muzhchine'          => 'В подарок мужчине',
              'komnatnoe-rastenie-v-podarok-na-yubilej'         => 'На юбилей',
              'komnatnoe-rastenie-v-podarok-nachalniku'         => 'Начальнику',
              'komnatnoe-rastenie-v-podarok-na-1-sentyabrya'    => 'День знаний',
              'komnatnoe-rastenie-v-podarok-na-den-uchitelya'   => 'День учителя',
              'komnatnoe-rastenie-v-podarok-na-den-materi'      => 'День матери',
              'komnatnoe-rastenie-v-podarok-na-novyj-god'       => 'Новый год',
              'komnatnoe-rastenie-v-podarok-na-den-svyatogo-valentina' => 'День святого Валентина',
              'komnatnoe-rastenie-v-podarok-na-8-marta'         => '8 марта',
            ] );
          ?>
        </div>
        <?php echo "<!-- Timing: after Повод для подарка = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>
      </div>
    </nav>
    <?php echo "<!-- Timing: sub-cats plants = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>
    <nav class="catalog-dropdown__sub-cats-wrap" data-menu="menu_item_gorshki">
      <div class="catalog-dropdown__sub-cats-header icon icon--arrow-right h5">
        <?php 
          plnt_get_menu_link([
            'slug' => 'gorshki_i_kashpo', 
            'classes' => 'cats-sub-menu__item-image'
          ]); 
        ?>
      </div>
      <div class="catalog-dropdown__sub-cats-body">
        <div class="catalog-dropdown__sub-cats-inner">
          <?php 
            plnt_get_menu_link([
              'slug' => 'gorshki_i_kashpo', 
              'classes' => 'cats-sub-menu__heading cats-sub-menu__item-image'
            ]); 
            plnt_get_menu_link([
              'slug' => 'kashpo-s-avtopolivom', 
              'classes' => 'cats-sub-menu__heading cats-sub-menu__item-image', 
              'taxonomy' => 'product_tag'
            ]);
          ?>
          <div class="cats-sub-menu">
            <span class="cats-sub-menu__heading">По диаметру</span>
            <?php get_primary_submenu([
              'slug'=>'gorshki_i_kashpo', 
              'show_heading' => false
              ]); ?>
          </div>
          <div class="cats-sub-menu">
            <span class="cats-sub-menu__heading">Популярные цвета</span>
            <?php plnt_render_colors_attr_menu(); ?>
          </div>
        </div>
        <div class="catalog-dropdown__sub-cats-inner">
          <?php //plnt_get_menu_link('kashpo-treez', [], 'cats-sub-menu__heading cats-sub-menu__item-image');?>
          <?php get_primary_submenu([
            'slug'=> 'treez-effectory', 
            'words_to_remove' => ['Treez Effectory ']
            ]);?>
          <?php get_primary_submenu([
            'slug' => 'treez-ergo', 
            'words_to_remove' => ['Treez Ergo ']
            ]);?>
        </div>
        <div class="catalog-dropdown__sub-cats-inner">
          <?php get_primary_submenu([
            'slug' => 'kashpo-lechuza', 
            'words_to_remove' => ['Кашпо Lechuza ']
            ]);?>
        </div>
      </div>
    </nav>
      <?php echo "<!-- Timing: sub-cats gorshki = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>
    <nav class="catalog-dropdown__sub-cats-wrap" data-menu="menu_item_treez_plants">
      <div class="catalog-dropdown__sub-cats-header icon icon--arrow-right h5">
        <?php  
          plnt_get_menu_link([
            'slug' => 'iskusstvennye-rasteniya-treez', 
            'classes' => 'cats-sub-menu__item-image'
          ]); 
        ?>
      </div>
      <div class="catalog-dropdown__sub-cats-body">
        <?php plnt_render_treez_plants_menu();?>
      </div>
    </nav>
    <?php echo "<!-- Timing: sub-cats treez_plants = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>
    <nav class="catalog-dropdown__sub-cats-wrap" data-menu = "menu_az_palnts">
      <div class="catalog-dropdown__sub-cats-header icon icon--arrow-right h5">
        <span>Растения от А до Я</span>
      </div>
      <div class="catalog-dropdown__sub-cats-body custom-scroll">
        <?php get_az_palnts_submenu(); ?>
      </div>
    </nav>
    <nav class="catalog-dropdown__sub-cats-wrap" data-menu = "menu_gift_card">
      <div class="catalog-dropdown__sub-cats-header icon icon--arrow-right h5">
        <span>Подарочный сертификат</span>
      </div>
      <div class="catalog-dropdown__sub-cats-body custom-scroll">
        <ul class="cats-sub-menu__list cats-sub-menu__list--column">
          <li class="cats-sub-menu__item">
            <a class="cats-sub-menu__item-link" href="<?php echo site_url()?>/shop/gift-card">Купить подарочный сертификат</a>
          </li>
          <li class="cats-sub-menu__item">
            <a class="cats-sub-menu__item-link" href="<?php echo site_url()?>/gift-card">Проверить баланс</a>
          </li>
          <li class="cats-sub-menu__item">
            <a class="cats-sub-menu__item-link" href="<?php echo site_url()?>/gift-card-info">О подарочном сертификате</a>
          </li>
        </ul>
      </div>
    </nav>
    <nav class="catalog-dropdown__sub-cats-wrap" data-menu = "menu_services">
      <div class="catalog-dropdown__sub-cats-header icon icon--arrow-right h5">
        <span>Услуги</span>
      </div>
      <div class="catalog-dropdown__sub-cats-body custom-scroll">
        <ul class="cats-sub-menu__list cats-sub-menu__list--column">
          <li class="cats-sub-menu__item">
            <a class="cats-sub-menu__item-link" href="<?php echo site_url()?>/pokupka-komnatnyh-rastenij-optom/">Оптовая покупка</a>
          </li>
          <li class="cats-sub-menu__item">
            <a class="cats-sub-menu__item-link" href="<?php echo site_url()?>/professionalnyj-uhod-za-rasteniyami/">Профессиональный уход</a>
          </li>
          <li class="cats-sub-menu__item">
            <a class="cats-sub-menu__item-link" href="<?php echo site_url()?>/landscaping">Озеленение</a>
          </li>
          <li class="cats-sub-menu__item">
            <a class="cats-sub-menu__item-link" href="<?php echo site_url()?>/usluga-peresadki-komnatnyh-rastenij/">Пересадка</a>
          </li>
        </ul>
      </div>
    </nav>
    <?php echo "<!-- Timing: sub-cats menu_az_palnts = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>
  </div>
  <?php echo "<!-- Timing: sub-cats = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>
  <div class="catalog-dropdown__image darken">
    <img class = "cats-sub-menu__img"
    src="https://plantis-shop.ru/wp-content/uploads/2026/03/fikus-lirata-kolumnaris-na-shtambe-21-95-1-300x300.webp" 
    alt=""
    width="300"
    height="300">
  </div>
</nav>

 <?php echo "<!-- Timing: primary menu = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>