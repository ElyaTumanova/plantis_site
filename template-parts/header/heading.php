<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$close_icon = carbon_get_theme_option('close_icon');
$logo = carbon_get_theme_option('logo');
?>

<header id="header" class="header">
  <div class="header__desktop">
    <div class="header__info">
      <div class="header__info-wrap container">
        <?php get_template_part( 'template-parts/menu/info-menu');?>
        <div class="header__info-contacts">
          <span class="header__info-adress icon icon--pre icon--location">
            <?php echo plnt_adress_link(); ?>
          </span>
          <div class="header__info-phones">
            <?php echo plnt_phones_link();?>
          </div>
          <?php get_template_part('template-parts/social-media-btns');?>
        </div>
      </div>
    </div>
    <div class="header__notice-wrap">
      <?php get_template_part( 'template-parts/header/header-notice' );?>
    </div>

    <div class="header__main" data-js-search>
      <div class="header__main-top container">
        <div class="header__main-wrap">
          <div class="header__main-logo logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo__link">
              <img 
              src="<?php echo $logo ?>" 
              class="logo__img" 
              alt="Plantis" 
              width="150" 
              height="28">
            </a>
          </div>

          <div class="header__main-menu">
            <button class="header__main-catalog-btn button catalog-dropdown-open-btn"
              type="button"
            >
              <span class="burger">
                <span class="burger__line"></span>
                <span class="burger__line"></span>
              </span>
              Каталог
            </button>
            <?php get_template_part( 'template-parts/menu/secondary-menu');?>
          </div>
        </div>

        <div class="header__main-wrap">
          <div class="header__main-search search">
            <?php plnt_search_form( 'searchform-desktop' ); ?>
            <? //get_search_form(); ?>
            <div class="search__icon search__icon--search"><?php echo plnt_icon('search') ?></div>
            <div class="search__icon search__icon--close"><?php echo plnt_icon('close') ?></div>
          </div>
          <div class="header__main-btns-wrap">
              <?php plnt_account_button();?>
              <?php plnt_wishlist_button();?>
              <?php plnt_woocommerce_cart_header();?>         
          </div>
        </div>
      </div>
      <div class="header__main-bottom container">
        <?php get_template_part( 'template-parts/menu/cats-menu');?>
      </div>
      <div class="header__main-catalog-dropdown">
        <?php get_template_part( 'template-parts/menu/catalog-menu');?>
      </div>
      <div class="search-result search-result--desktop"></div>
    </div>

  
  </div>

  <div class="header__mob container">
    <?php plnt_account_button('burger-menu__account');?>
    <div class="header__mob-logo logo">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo__link">
        <img 
        src="<?php echo $logo ?>" 
        class="logo__img" 
        alt="Plantis" 
        width="130" 
        height="24"></a>
    </div>
    <button class="header__mob-menu burger-menu-open-btn button button--clean"
      type="button"
    >
      <span class="burger">
        <span class="burger__line"></span>
        <span class="burger__line"></span>
      </span>
    </button>
  <div class="header__notice-wrap_mob">
    <?php get_template_part( 'template-parts/header/header-notice' );?>
  </div>
  <!-- <div class="search__wrap">
    <?php //get_search_form();?>
    <div class="search__clean"><?php //echo $close_icon ?></div>
  </div> -->

  <!-- <div class="search-result-popup popup">
    <div class="search-result">
    </div>
  </div> -->
  
  <div class="header__nav container">
    <div class="header__nav-actions-wrap">
      <?php echo plnt_wishlist_button();?>
      <span class="header__nav-actions-label">Избранное</span>
    </div>	

    <div class="header__nav-actions-wrap side-cart-popup-open-btn">
      <?php plnt_woocommerce_cart_header_mob(); ?>
      <span class="header__nav-actions-label">Корзина</span>
    </div>

    <div class="header__nav-actions-wrap header__catalog_mob">	
      <?php echo plnt_icon('catalog'); ?>	
      <span class="header__nav-actions-label">Каталог</span>		
    </div>

    <div class="header__nav-actions-wrap search-popup-open-btn">	
      <?php echo plnt_icon('search'); ?>	
      <span class="header__nav-actions-label">Поиск</span>		
    </div>

    <div class="header__nav-actions-wrap">
        <a href="tel:+79995527944">	
             <?php echo plnt_icon('phone') ?>	
        </a>
        <span class="header__nav-actions-label">Позвонить</span>	
      </a>
    </div>
  </div>
</header><!-- #header -->
	
