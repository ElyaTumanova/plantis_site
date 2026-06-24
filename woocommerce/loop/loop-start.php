<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 * 
 * MODIFIED FOR PLANTIS THEME
 * добавляем атрибуты schema.org
 * управляем классами
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php
$start_time = microtime( true );

$products_classes = 'products columns-' . wc_get_loop_prop( 'columns' );

if ( plnt_get_catalog_type() === 'plants' ) {
  $products_classes = 'products in-row';
} elseif ( is_product_tag() ) {
  $term = get_queried_object();

  $allowed_tags = array(
    // Популярные подборки
    'napolnye',
    'novichkam',
    'pet-friendly',
    'malenkie-cvety-v-gorshkah',
    'ampelnye',
    'ehkzoticheskie-komnatnye-rasteniya',
    'variegatnye',
    'bonsay',

    // Повод для подарка
    'komnatnoe-rastenie-v-podarok-zhenshchine',
    'komnatnoe-rastenie-v-podarok-muzhchine',
    'komnatnoe-rastenie-v-podarok-na-yubilej',
    'komnatnoe-rastenie-v-podarok-nachalniku',
    'komnatnoe-rastenie-v-podarok-na-1-sentyabrya',
    'komnatnoe-rastenie-v-podarok-na-den-uchitelya',
    'komnatnoe-rastenie-v-podarok-na-den-materi',
    'komnatnoe-rastenie-v-podarok-na-novyj-god',
    'komnatnoe-rastenie-v-podarok-na-den-svyatogo-valentina',
    'komnatnoe-rastenie-v-podarok-na-8-marta',
  );

  if (
    $term
    && ! is_wp_error( $term )
    && in_array( $term->slug, $allowed_tags, true )
  ) {
    $products_classes = 'products in-row';
  }
}

$ctx = plnt_get_catalog_context();

$image_url = get_template_directory_uri() . '/images/interior.webp';

if ( ! empty( $ctx['term'] ) ) {
    $thumbnail_id = get_term_meta( $ctx['term']->term_id, 'thumbnail_id', true );

    if ( $thumbnail_id ) {
        $thumbnail_url = wp_get_attachment_url( $thumbnail_id );

        if ( $thumbnail_url ) {
            $image_url = $thumbnail_url;
        }
    }
}

?>

<ul
  class="<?php echo esc_attr( $products_classes ); ?>"
  itemscope
  itemtype="https://schema.org/OfferCatalog">

  <?php if ( $ctx ) : ?>
      <meta itemprop="name" content="<?php echo esc_attr( $ctx['title'] ); ?>">

      <meta
          itemprop="description"
          content="<?php echo esc_attr( $ctx['desc'] ?: $ctx['title'] ); ?>">

      <meta
          itemprop="image"
          content="<?php echo esc_url( $image_url ); ?>">
  <?php endif; ?>

<?php 
  $execution_time = round( ( microtime( true ) - $start_time ) * 1000, 2 );
?>

<!-- Loop render time: <?php echo $execution_time; ?> ms -->
