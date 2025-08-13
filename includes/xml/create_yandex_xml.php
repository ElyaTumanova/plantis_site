<?php
// if ( ! defined( 'ABSPATH' ) ) {
// 	exit; // Exit if accessed directly
// }
require_once $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


global $plants_cat_id;
global $gorshki_cat_id;
global $ukhod_cat_id;
global $treez_cat_id;
global $treez_poliv_cat_id;
global $plants_treez_cat_id;
global $lechuza_cat_id;
global $misc_cat_id;
global $peresadka_cat_id;
global $uncategorized_cat_id;
$yandex_xml = "<?xml version='1.0' encoding='UTF-8'?>
<yml_catalog date='".date('Y-m-d H:i')."'>
<shop>
<name>Plantis</name>
<company>ИП Туманов В.В.</company>
<url>https://plantis-shop.ru</url>
<currencies>
<currency id='RUB' rate='1'/>
</currencies>
";

$yandex_xml .="<categories>
";

$categories_with_stock = [];

$args = [
    'taxonomy'   => 'product_cat',
    'hide_empty' => true, // только категории с товарами
];

$product_cats = get_terms( $args );

foreach ( $product_cats as $cat ) {
    $products = wc_get_products([
        'status'      => 'publish',
        'limit'       => -1,
        'stock_status'=> 'instock',
        'category'    => [ $cat->slug ],
    ]);

    // Фильтрация по количеству на складе
    foreach ( $products as $product ) {
        if ( $product->get_stock_quantity() > 0) {
            $categories_with_stock[] = $cat;
            break;
        }
    }
}

$args=array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => true,
    'include' => $categories_with_stock,
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
<delivery>true</delivery>
";
//списоск опций доставки

global $delivery_inMKAD;
global $delivery_outMKAD;
$urgent_markup_delivery = carbon_get_theme_option('urgent_markup_delivery');
$urgent_markup_delivery_large = carbon_get_theme_option('urgent_markup_delivery_large');
$large_markup_delivery_out_mkad = carbon_get_theme_option('large_markup_delivery_out_mkad');
$shipping_costs = plnt_get_shiping_costs();
$in_mkad = $shipping_costs[$delivery_inMKAD];
$out_mkad = $shipping_costs[$delivery_outMKAD];
$out_mkad_urg = floatval(str_replace(' ', '', $out_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery));

$out_mkad_large = floatval(str_replace(' ', '', $out_mkad)) + floatval(str_replace(' ', '', $large_markup_delivery_out_mkad));
$out_mkad_urg_large = floatval(str_replace(' ', '', $out_mkad)) + floatval(str_replace(' ', '', $large_markup_delivery_out_mkad)) + floatval(str_replace(' ', '', $urgent_markup_delivery_large));

$yandex_xml .= 
"<delivery-options>
    <option cost='".$out_mkad."' days = '1' order-before='20'/>
    <option cost='".$out_mkad_urg."' days = '0' order-before='18'/>
</delivery-options>

<pickup-options>
    <option cost='0' days='0' order-before='18'/>
</pickup-options>
";

// Список товаров. В примере участвует кастомный тип записи 'products', его замените на тот, который создан у вас.
$yandex_xml .= "<offers>
";
$args=array(
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'meta_query' => array( 
        array(
            'key' => '_stock',
            'type'    => 'numeric',
            'value' => '0',
            'compare' => '>'
        )
    ),
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'operator' => 'NOT IN',
            'terms' => [$treez_poliv_cat_id, $plants_treez_cat_id, $peresadka_cat_id, $misc_cat_id, $uncategorized_cat_id],
            'include_children' => 1,
        )
    )
);
$query = new WP_Query;
$allproducts = $query->query($args);

$allproductscount = count($allproducts);

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

    $brand = '';
    $cats = get_the_terms($allproduct->ID,'product_cat');
    var_dump($cats);
    $idCats = [];
    foreach ($cats as $cat) {
        array_push($idCats, $cat -> 'term_id');
    }
    var_dump($idCats);
    if (in_array($treez_cat_id, $idCats) || in_array($treez_poliv_cat_id, $idCats) || in_array($plants_treez_cat_id, $idCats)) {
        $brand = 'Treez';
    } else if (in_array($lechuza_cat_id, $idCats)) {
        $brand = 'Lechuza';
    } else {
        $brand = 'Plantis';
    }
    
    $yandex_xml .= 
    "<offer id='".$allproduct->ID."' available='true'>
    <url>".get_permalink($allproduct->ID)."</url>";

    // Получаем цену товара
    $sale = get_post_meta($allproduct->ID, '_sale_price', true);
    if ($sale>0) {
        $yandex_xml .= "<price>".number_format(get_post_meta($allproduct->ID,'_sale_price',true), 2, '.', '')."</price>
        <oldprice>".number_format(get_post_meta($allproduct->ID,'_regular_price',true), 2, '.', '')."</oldprice>";
    } else {
        $yandex_xml .= "<price>".number_format(get_post_meta($allproduct->ID,'_price',true), 2, '.', '')."</price>";
    };
    $yandex_xml .= 
    "<currencyId>RUB</currencyId>
    <categoryId>".$lastcateg."</categoryId>
    ";

    // Получаем картинку товара. Если она у вас хранится в мета поле, берите из него.
    $product_img=wp_get_attachment_image_src(get_post_thumbnail_id($allproduct->ID),'full');  

    if(is_array($product_img) && $product_img[0])
    $yandex_xml .= "<picture>".$product_img[0]."</picture>
    ";

    // Способы доставки для групногабаритного товара
    if(get_post_meta($allproduct->ID,'_weight',true)>=11) {
        $yandex_xml .= "<delivery-options>
            <option cost='".$out_mkad_large."' days = '1' order-before='20'/>
            <option cost='".$out_mkad_urg_large."' days = '0' order-before='18'/>
        </delivery-options>
        ";
    }
    //Название и описание
    $yandex_xml .= "<name>".htmlspecialchars($allproduct->post_title)."</name>
    <description><![CDATA['".htmlspecialchars(strip_tags($allproduct->post_content))."]]></description>
    <vendor>".$brand."</vendor>";

    //Параметры товара

    $product_attributes = get_post_meta($allproduct->ID, '_product_attributes', true);
    if(is_array($product_attributes)) {
        foreach ($product_attributes as $product_attribute) {
            if($product_attribute['is_visible']) {
                $param_name = wc_attribute_label( $product_attribute['name'] );
                if($product_attribute['is_taxonomy']) {
                    $attribute_values = get_the_terms( $allproduct->ID, $product_attribute['name']);
                    $values = [];
                    foreach ($attribute_values as $value) {
                        $values[] = $value->name;
                    };
                    $param_value = implode(',', $values);
                } else {
                    $param_value =  $product_attribute['value'];
                }
                $yandex_xml .= '<param name ="'.$param_name.'">'.$param_value.'</param>';
            };
        }
    }

    //Закрыли тег оффер
    $yandex_xml .= "</offer>
    ";
}
//Конец файла
$yandex_xml .= "</offers>
";
$yandex_xml .= "</shop>
</yml_catalog>
";

$fp = fopen( ABSPATH . "/wp-content/yandex-xml/feed-yml-0.xml", 'w' ); 
fwrite( $fp, $yandex_xml );
fclose( $fp );



