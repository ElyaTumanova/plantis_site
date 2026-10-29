<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/** Добавляет HTML-обёртку вокруг блока WooCommerce.
  * @param string $before_hook Хук, где открыть обёртку.
  * @param string $after_hook  Хук, где закрыть обёртку.
  * @param string $class       CSS-класс обёртки.
  * @param int    $before_priority Приоритет открытия.
  * @param int    $after_priority  Приоритет закрытия.
  * @param string    $attributes  Прочие атрибуты тега.
  */
  function plnt_add_wrapper (
    $class,
    $before_hook,
    $before_priority,
    $after_hook,
    $after_priority,
    $attributes = ''
  ) {
    add_action($before_hook, function() use ($class, $attributes) {
      ?>
      <div class="<?php echo esc_attr($class); ?>" <?php echo $attributes; ?> >
      <?php
    }, $before_priority);

    add_action($after_hook, function() use ($class, $attributes) {
      ?>
      </div><!-- .<?php echo esc_attr($class); ?> -->
      <?php
    }, $after_priority);
  }
  
  function plnt_add_section(
    $class,
    $before_hook,
    $before_priority,
    $after_hook,
    $after_priority,
    $attributes = ''
  ) {
    add_action($before_hook, function() use ($class, $attributes) {
      ?>
      <section class="<?php echo esc_attr($class); ?>" <?php echo $attributes; ?>>
      <?php
    }, $before_priority);

    add_action($after_hook, function() use ($class) {
      ?>
      </section><!-- .<?php echo esc_attr($class); ?> -->
      <?php
    }, $after_priority);
  }


/**
 * Delivery configuration from the ACF Options page.
 * One ACF read per field per PHP request. No dependence on WooCommerce session.
 * Preserve intentional empty values (e.g. the free delivery threshold).
 *
 * Important: saved ACF options must be populated before removing Carbon fields.
 */
function plnt_delivery_option( $name, $default = null ) {
    static $cache = array();

    if ( array_key_exists( $name, $cache ) ) {
        return $cache[ $name ];
    }

    if ( ! function_exists( 'get_field' ) ) {
        return $default;
    }

    $value = get_field( $name, 'option' );
    $cache[ $name ] = $value === null ? $default : $value;

    return $cache[ $name ];
}
