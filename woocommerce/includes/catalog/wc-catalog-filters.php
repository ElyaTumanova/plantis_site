<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// #filters добавляем классы к фильтрам #berocket для работы слайдера #swiper

add_filter('BeRocket_AAPF_template_full_content', 'some_custom_berocket_aapf_template_full_content', 4000, 1);
add_filter('BeRocket_AAPF_template_full_element_content', 'some_custom_berocket_aapf_template_full_content', 4000, 1);
function some_custom_berocket_aapf_template_full_content($template_content) {
  $data_name = $template_content['template']['attributes']['data-name'] ?? '';
	if ($data_name ==='Подборки-слайдер') {
			array_push($template_content['template']['content']['filter']['attributes']['class'],'metki_swiper_wrap');
			array_push($template_content['template']['content']['filter']['attributes']['class'],'swiper');
			
			$template_content['template']['content']['filter']['content']['list']['attributes']['class'] = 'swiper-wrapper';
	
			$elements = $template_content['template']['content']['filter']['content']['list']['content'];
			$new_elements = [];
			$i = 0;
			foreach($elements as $element) {
				$element['attributes']['class'] = 'swiper-slide';
				$new_elements[$i] = $element;
				$i++;
			}
			$template_content['template']['content']['filter']['content']['list']['content'] = $new_elements;
	
			$template_content['template']['content'] = berocket_insert_to_array(
				$template_content['template']['content'],
				'filter',
				array(
					'custom_content' => '<div class="swiper-scrollbar"></div>'
				),
				true
			);
	
			// echo '<pre>';
			// print_r( $template_content );
			// echo '</pre>';
	}
    return $template_content;
}

add_filter('BeRocket_AAPF_template_full_content', 'plnt_plant_name_filter_content', 4000, 1);
add_filter('BeRocket_AAPF_template_full_element_content', 'plnt_plant_name_filter_content', 4000, 1);
function plnt_plant_name_filter_content($template_content) {
  $data_name = $template_content['template']['attributes']['data-name'] ?? '';
	if ($data_name ==='Название') {
      // echo '<pre>';
			// print_r( $template_content['template']['content'] );
			// echo '</pre>';

        $template_content['template']['content'] = berocket_insert_to_array(
				$template_content['template']['content'],
          'filter',
          array(
            'custom_content' =>  '<div class="berocket-search-field"> <input name="berocket-search-input" type="text" placeholder="Поиск по названию" class="berocket-search-input d-none"></div>'
          ),
          true
			  );
        // $template_content['template']['content']['filter']['content'] = berocket_insert_to_array(
				// $template_content['template']['content']['filter']['content'],
        //   'list',
        //   array(
        //     'custom_content' =>  '<input type="text" placeholder="Поиск..." class="berocket-search-input">'
        //   ),
        //   true
			  // );
	}
    return $template_content;

}
add_filter('BeRocket_AAPF_template_full_content', 'plnt_rename_filter_title', 4000, 1);
add_filter('BeRocket_AAPF_template_full_element_content', 'plnt_rename_filter_title', 4000, 1);

function plnt_rename_filter_title( $template_content ) {
	if (
		isset( $template_content['template']['content']['header']['content']['title']['content']['title'] ) &&
		$template_content['template']['content']['header']['content']['title']['content']['title'] === 'Подборки'
	) {
		$template_content['template']['content']['header']['content']['title']['content']['title'] = 'Подарок';
	}

	return $template_content;
}

// add_filter('BeRocket_AAPF_template_full_content', 'plnt_berocket_gift_filter_header', 4000, 1);
// add_filter('BeRocket_AAPF_template_full_element_content', 'plnt_berocket_gift_filter_header', 4000, 1);
// function plnt_berocket_gift_filter_header($template_content) {
// 	if ($template_content['template']['attributes']['id']==='bapf_12') {
//     $template_content['template']['attributes']['data-name'] = 'В подарок';
// 	// echo '<pre>';
// 	// print_r( $template_content );
// 	// echo '</pre>';
// 	}
//     return $template_content;
// }


// add_filter('BeRocket_AAPF_template_full_content', 'plnt_berocket_active_filter', 4000, 1);
// add_filter('BeRocket_AAPF_template_full_element_content', 'plnt_berocket_active_filter', 4000, 1);
// function plnt_berocket_active_filter($template_content) {
// 	if ($template_content['template']['attributes']['data-name']==='Активные фильтры') {
//     $template_content['template']['attributes']['id'] = 'active_id';
// 	// echo '<pre>';
// 	// print_r( $template_content );
// 	// echo '</pre>';
// 	}
//     return $template_content;
// }
