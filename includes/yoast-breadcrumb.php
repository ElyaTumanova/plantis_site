<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//убираем стандартные крошки WC
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );


//Хлебные крошки #breadcrumb от Yoast
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
		$ul = '<ul class="woocommerce-breadcrumb" id="breadcrumbs" itemscope="itemscope" itemtype="https://schema.org/BreadcrumbList" class="breadcrumb">%s</ul>';

		// Вставляем в контейнер li элменты
		$html = sprintf( $ul, $html );

		return $html;
	}
}

new Pretty_Breadcrumb();


add_action( 'woocommerce_before_main_content', 'plnt_breadrumbs_yoast', 10 );
function plnt_breadrumbs_yoast() {
    if ( is_product() || is_product_category() ||is_product_tag() || is_shop() || is_tax()) {
        do_action('pretty_breadcrumb');
    }
}


// //Изменение заголовка в хлебных крошках Yoast SEO #breadcrumb
add_filter( 'wpseo_breadcrumb_links', 'plnt_change_breadcrumb_title', 10, 2 );
function plnt_change_breadcrumb_title( $links ) {
    $new_links = [];
    foreach($links as $link) {
        if(array_key_exists('taxonomy', $link)){
            if ($link['taxonomy'] == 'pa_color') {
                $new_text = plnt_get_color_name_tltle($link['text']);
                $link['text'] = "Горшки и кашпо ".$new_text." цвета";
            }
        }

        array_push($new_links, $link);
    }
	return $new_links;
}

// старый код для стандарта WC
// изменяем названия меток на подборки для хлебных крошек #breadcrumb
// add_filter( 'woocommerce_get_breadcrumb', 'plnt_woocommerce_get_breadcrumb_filter', 10, 2 );

// function plnt_woocommerce_get_breadcrumb_filter( $crumbs, $that ){
// 	foreach ( $crumbs as $crumb ) {
// 		if (str_contains($crumb[0], 'Товары с меткой ')) {
// 			$key = array_search($crumb, $crumbs);
// 			$newstring = str_replace('Товары с меткой ', "Товары из подборки ", $crumb[0]);

// 			$replacements = array(0 => $newstring);

// 			$crumbNew = array_replace($crumb, $replacements);
// 			$replacements2 = array($key => $crumbNew);
// 			$crumbsNew = array_replace($crumbs, $replacements2);
// 			$crumbs = $crumbsNew;
// 		}
// 	}

// 	return $crumbs;
// }