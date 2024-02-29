<?php
// if ( ! defined( 'ABSPATH' ) ) {
// 	exit; // Exit if accessed directly
// }

function create_yandex_xml () {
	?>
	<button class="my_test_button">Click me</button>
	<script>
		const element = document.querySelector('.my_test_button')

		element.addEventListener('click', function (event) {
		console.log('Произошло событие', event.type);


		<?php 
		$yandex_xml .= "<?xml version='1.0' encoding='UTF-8'?>
        <!DOCTYPE yml_catalog SYSTEM 'shops.dtd'>
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
        );
        $query = new WP_Query;
        $allproducts = $query->query($args);
        foreach($allproducts as $allproduct){
            // Определяем последую категорию в дереве, к которой присвоен конкретный товар в текущем цикле. В примере участвует кастомная таксономия 'products_category', её замените на ту, которая создана у вас.
            // $lastcateg='';
            // if($categorys=get_the_terms($allproduct->ID,'products_category')){
            //     if(count($categorys)>1){
            //         $arrtemp=array();
            //         foreach($categorys as $category){
            //             $arrtemp[]=$category->term_id;
            //         }
                    
            //         foreach($arrtemp as $arrtempz){
            //             $termchildren=get_term_children($arrtempz,'products_category');
            //             if($termchildren==null){
            //                 $lastcateg=$arrtempz;
            //                 break;
            //             }

            //             foreach($termchildren as $child){
            //                 if(!in_array($child,$arrtemp)){
            //                     $lastcateg=$arrtempz;
            //                     break 2;
            //                 }
            //             }
            //         }     
            //     }else{
            //         $lastcateg=$categorys[0]->term_id;
            //     }
            // }  
            
            // Получаем картинку товара. Если она у вас хранится в мета поле, берите из него.
            $product_img=wp_get_attachment_image_src(get_post_thumbnail_id($allproduct->ID),'full');  

            $yandex_xml .= 
            "
            <offer id='".$allproduct->ID."' available='true'>
            <url>".get_permalink($allproduct->ID)."</url>
            <price>".get_post_meta($allproduct->ID,'products_price',true)."</price>
            <currencyId>RUR</currencyId>
            <categoryId></categoryId>
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









		$fp = fopen( ABSPATH . "/wp-content/yandex-xml/lalalal.xml", 'w' );
		fwrite( $fp, $yandex_xml );
		fclose( $fp );
		?>
		})
	</script>
	<?php
}

add_action( 'wp_footer', 'create_yandex_xml' );

// $dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
// $root = $dom->createElement("yml_catalog"); // Создаём корневой элемент
// $root->setAttribute("date", date('Y-m-d H:i'));
// $dom->appendChild($root);
// $shop = $dom->createElement("shop");
// $root->appendChild($shop);

// //Добавляем элементы в shop
// $name = $dom->createElement("name", get_bloginfo('name'));
// $shop->appendChild($name);
// $company = $dom->createElement("company", get_bloginfo('name'));
// $shop->appendChild($company);
// $url = $dom->createElement("url", get_bloginfo('url'));
// $shop->appendChild($url);


// $currencies = $dom->createElement("currencies");
// $shop->appendChild($currencies);
// $currency = $dom->createElement("currency");
// $currency->setAttribute("id", 'RUR');
// $currency->setAttribute("rate", '1');
// $currency->setAttribute("rate", '1');
// $currencies->appendChild($currency); 

// //Добавляем категории
// $categories = $dom->createElement("categories");
// $shop->appendChild($categories);

// //список категорий
// $args=array(
// 	'taxonomy'   => 'product_cat',
//     'hide_empty' => true,
// );
// $cats=get_terms($args);

// foreach($cats as $item) {
//     $category = $dom->createElement("category",$item->name);
//     $category->setAttribute("id", $item->term_id);
//     if($item->parent!=0){
//         $category->setAttribute("parentId", $item->parent);
// 	}
//     $categories->appendChild($category);
// }

//   $logins = array("User1", "User2", "User3"); // Логины пользователей
//   $passwords = array("Pass1", "Pass2", "Pass3"); // Пароли пользователей
//   for ($i = 0; $i < count($logins); $i++) {
//     $id = $i + 1; // id-пользователя
//     $user = $dom->createElement("user"); // Создаём узел "user"
//     $user->setAttribute("id", $id); // Устанавливаем атрибут "id" у узла "user"
//     $login = $dom->createElement("login", $logins[$i]); // Создаём узел "login" с текстом внутри
//     $password = $dom->createElement("password", $passwords[$i]); // Создаём узел "password" с текстом внутри
//     $user->appendChild($login); // Добавляем в узел "user" узел "login"
//     $user->appendChild($password);// Добавляем в узел "user" узел "password"
//     $root->appendChild($user); // Добавляем в корневой узел "users" узел "user"
//   }
//   $dom->save("wp-content/yandex-xml/users1.xml"); // Сохраняем полученный XML-документ в файл
?>

