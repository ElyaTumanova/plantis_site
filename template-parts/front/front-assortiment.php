<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Получаем массив поля assort
$assort = get_field( 'assort' );

if ( ! empty( $assort ) && is_array( $assort ) ) :

  $taxonomy_map = [
    'cat'   => 'product_cat',
    'tag'   => 'product_tag',
    'brand' => 'product_brand',
  ];
?>

  <section class="section container">
    <h2 class="h2">Ассортимент Plantis.shop включает:</h2>

    <div class="front__assort swiper--over">
      <div class="swiper">
        <div class="front__assort-slider swiper-wrapper">

          <?php foreach ( $assort as $item ) : 
            $item_slug = trim( $item['name'] ?? '' );
            $item_type = $item['type'] ?? '';
            $title     = $item['title'] ?? '';
            $desc      = $item['description'] ?? '';
            $image_id  = $item['image'] ?? null;

            // Проверяем существование термина и получаем ссылку
            $term_link = false;
            if ( ! empty( $item_slug ) && isset( $taxonomy_map[ $item_type ] ) ) {
              $taxonomy = $taxonomy_map[ $item_type ];

              if ( term_exists( $item_slug, $taxonomy ) ) {
                $term = get_term_by( 'slug', $item_slug, $taxonomy );
                if ( $term && ! is_wp_error( $term ) ) {
                  $url = get_term_link( $term );
                  if ( ! is_wp_error( $url ) ) {
                    $term_link = $url;
                  }
                }
              }
            }
          ?>

            <div class="front__assort-wrap darken swiper-slide">
              <div class="front__assort-content">
                <?php if ( $title ) : ?>
                  <h3 class="h5 front__assort-heading"><?php echo esc_html( $title ); ?></h3>
                <?php endif; ?>

                <?php if ( $desc ) : ?>
                  <p class="front__assort-text"><?php echo esc_html( $desc ); ?></p>
                <?php endif; ?>
              </div>

              <?php if ( $image_id ) : ?>
                <?php 
                  echo wp_get_attachment_image( 
                    $image_id, 
                    'full', 
                    false, 
                    [
                      'class'  => 'front__assort-image',
                      'width'  => '316',
                      'height' => '316',
                    ] 
                  ); 
                ?>
              <?php endif; ?>

              <?php if ( $term_link ) : ?>
                <a class="front__assort-link" href="<?php echo esc_url( $term_link ); ?>" aria-label="<?php echo esc_attr( $title ); ?>"></a>
              <?php endif; ?>
            </div>

          <?php endforeach; ?>

        </div>
        <div class="swiper-scrollbar"></div>
      </div>
      <div class="swiper-button-prev"></div>
      <div class="swiper-button-next"></div>
    </div>
  </section>

<?php endif; ?>

