<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
//Хлебные крошки #breadcrumb
// if ( ! function_exists( 'ast_breadrumbs_yoast' ) ) {
// 	add_action( 'woocommerce_before_main_content', 'ast_breadrumbs_yoast', 10 );
// 	function ast_breadrumbs_yoast() {
// 		if ( is_product() || is_product_category() ||is_product_tag() || is_shop() || is_tax('pa_color')) {
// 			if ( function_exists( 'yoast_breadcrumb' ) ) {
// 				$before      = '<div class="woocommerce-breadcrumb" id="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">';
// 				$after       = '</div>';
// 				$breadcrumbs = yoast_breadcrumb( $before, $after, true );
				
// 				return $breadcrumbs;
// 			}
// 		}
// 	}
// }


remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

/**
 * Изменяет хлебные крошки Yoast.
 *
 * Вывести в шаблоне: do_action('pretty_breadcrumb');
 *
 */
class Pretty_Breadcrumb {

	/**
	 * Какую позицию занимает элемент в цепочке хлебных крошек.
	 *
	 * @var int
	 */
	private $el_position = 0;

	public function __construct() {
		add_action( 'pretty_breadcrumb', [ $this, 'render' ] );
	}

	/**
	 * Выводит на экран сгенерированные крошки.
	 *
	 * @return void
	 */
	public function render() {
		if ( ! function_exists( 'yoast_breadcrumb' ) ) {
			return;
		}

		// Регистрируем фильтры для изменения дефолтной вёрстки крошек
		add_filter( 'wpseo_breadcrumb_single_link', [ $this, 'modify_yoast_items' ], 10, 2 );
		add_filter( 'wpseo_breadcrumb_output', [ $this, 'modify_yoast_output' ] );
		add_filter( 'wpseo_breadcrumb_output_wrapper', [ $this, 'modify_yoast_wrapper' ] );
		add_filter( 'wpseo_breadcrumb_separator', '__return_empty_string' );

		// Выводим крошки на экран
		yoast_breadcrumb();

		// Отключаем фильтры
		remove_filter( 'wpseo_breadcrumb_single_link', [ $this, 'modify_yoast_items' ] );
		remove_filter( 'wpseo_breadcrumb_output', [ $this, 'modify_yoast_output' ] );
		remove_filter( 'wpseo_breadcrumb_output_wrapper', [ $this, 'modify_yoast_wrapper' ] );
		remove_filter( 'wpseo_breadcrumb_separator', '__return_empty_string' );

		// Обнуляем счётчик
		$this->el_position = 0;
	}

	/**
	 * Изменяет html код li элементов.
	 *
	 * @param string $link_html Дефолтная вёрстка элемента хлебных крошек.
	 * @param array  $link_data Массив данных об элементе хлебных крошек.
	 *
	 * @return string
	 */
	function modify_yoast_items( $link_html, $link_data ) {
		// Шаблон контейнера li
		$li = '<li itemprop="itemListElement" itemscope="itemscope" itemtype="https://schema.org/ListItem" %s>%s</li>';

		// Содержимое li в зависимости от позиции элемента
		if ( strpos( $link_html, 'breadcrumb_last' ) === false ) {
			$li_inner = sprintf( '
                <a itemprop="item" href="%s" class="pathway">
                    <span itemprop="name">%s</span>
                </a>
            ', $link_data['url'], $link_data['text'] );
			$li_inner .= '<span class="divider"> / </span>';
			$li_class = '';
		} else {
			$li_inner = sprintf( '<span itemprop="name">%s</span>', $link_data['text'] );
			$li_class = 'class="active"';
		}

		$li_inner .= sprintf( '<meta itemprop="position" content="%d"/>', ++ $this->el_position );

		// Вкладываем сформированное содержание в li и возвращаем полученный элемент хлебных крошек.
		return sprintf( $li, $li_class, $li_inner );
	}

	/**
	 * Возвращает псевдо wrapper, который в будущем будет вырезан из вёрстки.
	 * Если этого не сделать, то будущие li будут обёртнуты в единый span Yoast'ом.
	 *
	 * @return string
	 */
	function modify_yoast_wrapper() {
		return 'wrapper'; // Будущий "уникальный" тег для вырезки из html
	}

	/**
	 * Изменяет дефолтный html код крошек Yoast.
	 *
	 * @param string $html
	 *
	 * @return string
	 */
	function modify_yoast_output( $html ) {
		// Убираем псевдо wrapper
		$html = str_replace( [ '<wrapper>', '</wrapper>' ], '', $html );

		// Формируем контейнер для li элементов
		$ul = '<ul itemscope="itemscope" itemtype="https://schema.org/BreadcrumbList" class="breadcrumb">%s</ul>';

		// Вставляем в контейнер li элменты
		$html = sprintf( $ul, $html );

		return $html;
	}
}

new Pretty_Breadcrumb();

function plnt_add_lazy_attr ($attr) {
	$attr['loading'] = 'lazy';
	return $attr;
}

 add_filter( 'wp_get_attachment_image_attributes', 'plnt_add_lazy_attr', 10, 2);