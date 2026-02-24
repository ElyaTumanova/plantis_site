<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$close_icon = carbon_get_theme_option('close_icon');
$search_icon = carbon_get_theme_option('search_icon');
$logo = carbon_get_theme_option('logo');
$site_title = carbon_get_theme_option('site_title');
$account_icon = carbon_get_theme_option('account_icon');
$account_logged_icon = carbon_get_theme_option('account_logged_icon');
$menu_icon_mob = carbon_get_theme_option('menu_icon_mob');
$phone_icon = carbon_get_theme_option('phone_icon');
$catalog_icon = carbon_get_theme_option('catalog_icon');
?>

<header id="header" class="header" role="banner">
		<div class="header__desktop">
			<div class="header__info">
				<div class="header__info-wrap container">
					<nav class="header__info-navigation" role="navigation">
            <?php get_template_part( 'template-parts/info-menu-header');?>
					</nav>
          <div class="header__wrap">
            <?php get_template_part('template-parts/social-media-btns');?>
            <div class="header__phones"><a href="tel:+78002015790">8 800 201 57 90</a> | <a href="tel:+79995527944">8 999 552-79-44</a></div>
          </div>
          <!-- <div class="header__working-hours">Eжедневно с 10 до 20</div> -->
				</div>
			</div>
			<div class="header__notice-wrap">
				<?php get_template_part( 'template-parts/header-notice' );?>
			</div>

			<div class="header__main">
				<div class="container">
					<div class="header__wrap_logo">
						<div class="logo">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo__link"><img src="<?php echo $logo ?>" class="logo__img" alt="Plantis" width="150" height="26"></a>
						</div><!-- .logo -->
					</div>
					<div class="header__description">
						<?php if ( is_front_page()) : ?>
							<h1 class="site-title"><?php echo $site_title ?></h1>
						<?php else : ?>
							<p class="site-title"><?php echo $site_title ?></p>
						<?php endif; ?>
					</div><!-- .description -->
					<div class="header__wrap">
						<div class="search-btn">
							<button class="header-btn__wrap">
								<?php echo $search_icon ?>
								<?php echo $close_icon ?>
								<span class="header-btn__label">Поиск</span>		
							</button>
						</div>
						<div class="header__account">
							<?php if (!is_user_logged_in()) : 
								if (is_account_page()) :?>
									<a href ="<?php echo esc_url( home_url( '/my-account' ) ); ?>" class="header-btn__wrap">
										<?php echo $account_icon ?>	
										<span class="header-btn__label">Войти</span>		
									</a>
								<?php else :?> 
									<div class="header-btn__wrap login-btn login-popup-open-btn">
										<?php echo $account_icon ?>	
										<span class="header-btn__label">Войти</span>		
									</div>
								<?php endif; ?>
							<?php else :?>
								<a href ="<?php echo esc_url( home_url( '/my-account/orders' ) ); ?>" class="header-btn__wrap header-btn__wrap_active">
									<?php echo $account_icon ?>		
									<span class="header-btn__label">Войти</span>		
								</a>
							<?php endif; ?>
						</div>
						<div class="header__wishlist">
							<?php echo do_shortcode('[yith_wcwl_items_count]')?>
						</div>
						
						<div class="header-cart">
							<?php 
								plnt_woocommerce_cart_header(); 
								?><div class="mini-cart__wrap"> <?php
									plnt_woocommerce_mini_cart(); 
								?></div>
						</div><!-- .header-cart -->
					</div>
				</div>
        <?php get_template_part( 'template-parts/menu-primary-header');?>
			</div>
		</div>

		<div class="header__mob container">
			<div class="search-btn">
				<button class="header-btn__wrap header-btn__wrap_mob">
					<?php echo $search_icon ?>
          <?php echo $close_icon ?>
				</button>
			</div>
			<div class="logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo__link"><img src="<?php echo $logo ?>" class="logo__img" alt="Plantis" width="150" height="26"></a>
			</div><!-- .logo -->
			<div class="header__mob-menu burger-menu-open-btn">
				<button class="header-btn__wrap header-btn__wrap_mob">
					<img class="header-btn__icon" src="<?php echo $menu_icon_mob ?>" alt="menu" width="21" height="21">		
				</button>
			</div>
		</div>
		<div class="header__notice-wrap_mob">
			<?php get_template_part( 'template-parts/header-notice' );?>
		</div>
    <div class="search__wrap">
      <?get_search_form();?>
      <div class="search__clean"><?php echo $close_icon ?></div>
    </div>

    <div class="search-result-popup popup">
      <div class="search-result">
      </div>
    </div>

		<!-- <div class="header__breadcrumb container"><?php //woocommerce_breadcrumb() ?></div> -->
		
		<div class="header__nav-wrap container">
			<div class="header__phone">
					<a href="tel:+79995527944" class="header-btn__wrap">	
						<?php echo $phone_icon ?>
						<span class="header-btn__label">Позвонить</span>	
					</a>
				</a>
			</div>

			<div class="header__catalog_mob">
					<div class="header-btn__wrap">	
						<?php echo $catalog_icon ?>	
						<span class="header-btn__label">Каталог</span>		
					</div>
				</a>
			</div>

			<div class="header__wishlist">
				<?php echo do_shortcode('[yith_wcwl_items_count]')?>
			</div>
			
			<div class="header-cart side-cart-popup-open-btn">
				<?php 
					plnt_woocommerce_cart_header_mob(); 
				?>

			</div><!-- .header-cart -->
		</div>
	</header><!-- #header -->
	
