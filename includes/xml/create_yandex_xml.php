<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function create_yandex_xml () {
	?>
	<button class="my_test_button">Click me</button>
	<script>
        // const intervalId = setInterval(function() {
        // console.log('Я выполняюсь каждую секунду')
        // }, 1000);

		const element = document.querySelector('.my_test_button')

		element.addEventListener('click', function (event) {
		console.log('Произошло событие', event.type);


		<?php 
		$yandex_xml .= "<?xml version='1.0' encoding='UTF-8'?>
        <yml_catalog date='".date('Y-m-d H:i')."'>
        <shop>
        <name>".get_bloginfo('name')."</name>
        <url>".get_bloginfo('url')."</url>
        <currencies>
        <currency id='RUB' rate='1'/>
        </currencies>
        ";

        $yandex_xml .="<categories>
        ";

        $args=array(
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'exclude_tree'    => array(379,229),
        );
        $terms=get_terms($args);
        foreach($terms as $item){
            $yandex_xml .="<category id='".$item->term_id."'";
        
            if($item->parent!=0){
                $yandex_xml .= " parentId='".$item->parent."'";
            }
            $yandex_xml .= ">".htmlspecialchars(trim($item->name))."</category>
            ";
        }
        $yandex_xml .= "</categories>
        ";

        // Список товаров. В примере участвует кастомный тип записи 'products', его замените на тот, который создан у вас.
        $yandex_xml .= "<offers>
        ";
        $args=array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'operator' => 'NOT IN',
                    'terms' => [379,229],
                    'include_children' => 1,
                )
            )
        );
        $query = new WP_Query;
        $allproducts = $query->query($args);
        foreach($allproducts as $allproduct){
            // Определяем последую категорию в дереве, к которой присвоен конкретный товар в текущем цикле. В примере участвует кастомная таксономия 'products_category', её замените на ту, которая создана у вас.
            $lastcateg='';
            if($categorys=get_the_terms($allproduct->ID,'product_cat')){
                if(count($categorys)>1){
                    $arrtemp=array();
                    foreach($categorys as $category){
                        $arrtemp[]=$category->term_id;
                    }
                    
                    foreach($arrtemp as $arrtempz){
                        $termchildren=get_term_children($arrtempz,'product_cat');
                        if($termchildren==null){
                            $lastcateg=$arrtempz;
                            break;
                        }

                        foreach($termchildren as $child){
                            if(!in_array($child,$arrtemp)){
                                $lastcateg=$arrtempz;
                                break 2;
                            }
                        }
                    }     
                }else{
                    $lastcateg=$categorys[0]->term_id;
                }
            }  
            
            // Получаем картинку товара. Если она у вас хранится в мета поле, берите из него.
            $product_img=wp_get_attachment_image_src(get_post_thumbnail_id($allproduct->ID),'full');  

            $yandex_xml .= 
            "
            <offer id='".$allproduct->ID."' available='true'>
            <url>".get_permalink($allproduct->ID)."</url>";

            $sale = get_post_meta($allproduct->ID, '_sale_price', true);
            if ($sale>0) {
                $yandex_xml .= "<price>".get_post_meta($allproduct->ID,'_sale_price',true)."</price>
                <oldprice>".get_post_meta($allproduct->ID,'_regular_price',true)."</oldprice>";
            } else {
                $yandex_xml .= "<price>".get_post_meta($allproduct->ID,'_price',true)."</price>";
            };
            $yandex_xml .= 
            "
            <currencyId>RUR</currencyId>
            <categoryId>".$lastcateg."</categoryId>
            ";

            if($product_img[0])
            $yandex_xml .= "<picture>".$product_img[0]."</picture>
            ";

            $yandex_xml .= "<name>".htmlspecialchars($allproduct->post_title)."</name>
            <description><![CDATA['".htmlspecialchars(strip_tags($allproduct->post_content))."]]></description>
            </offer>
            ";
        }

        $yandex_xml .= "</offers>
        ";
        $yandex_xml .= "</shop>
        </yml_catalog>
        ";

		$fp = fopen( ABSPATH . "/wp-content/yandex-xml/feed-yml-0.xml", 'w' );
		fwrite( $fp, $yandex_xml );
		fclose( $fp );
		?>
		})
	</script>
	<?php
}

add_action( 'wp_footer', 'create_yandex_xml' );
?>