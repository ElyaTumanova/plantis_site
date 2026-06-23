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

    </ul>
  </div>
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
            <?php get_primary_submenu([
            'slug' => 'ukhod',
            ]);?>
        </div>
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
      </div>
    </nav>
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
          <?php plnt_get_menu_link('kashpo-treez', [], 'cats-sub-menu__heading cats-sub-menu__item-image');?>
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
    <nav class="catalog-dropdown__sub-cats-wrap" data-menu = "menu_az_palnts">
      <div class="catalog-dropdown__sub-cats-header icon icon--arrow-right h5">
        <span>Растения от А до Я</span>
      </div>
      <div class="catalog-dropdown__sub-cats-body custom-scroll">
        <?php get_az_palnts_submenu(); ?>
      </div>
    </nav>
  </div>
  <div class="catalog-dropdown__image darken">
    <img class = "cats-sub-menu__img"
    src="https://plantis-shop.ru/wp-content/uploads/2026/03/fikus-lirata-kolumnaris-na-shtambe-21-95-1-300x300.webp" 
    alt=""
    width="300"
    height="300">
  </div>
</nav>

 <?php echo "<!-- Timing: primary menu = " . round((microtime(true) - $start) * 1000, 2) . " ms -->"; ?>