<?php
// if ( ! defined( 'ABSPATH' ) ) {
// exit; // Exit if accessed directly
// }

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
global $uncategorized_cat_id;
global $peresadka_cat_id;
$google_xml = "<?xml version='1.0'?>
<rss xmlns:g='http://base.google.com/ns/1.0' version='2.0'>
<channel>
<title>Plantis</title>
<link>https://plantis-shop.ru</link>
<description>Интернет магазин комнатных растений с доставкой - Plantis</description>
";

// Список товаров. В примере участвует кастомный тип записи 'products', его замените на тот, который создан у вас.
$args=array(
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    // 'meta_query' => array( 
    //     array(
    //         'key' => '_stock',
    //         'type'    => 'numeric',
    //         'value' => '0',
    //         'compare' => '>'
    //     )
    // ),
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field' => 'id',
            'operator' => 'NOT IN',
            'terms' => [$treez_poliv_cat_id, $peresadka_cat_id, $misc_cat_id, $uncategorized_cat_id],
            'include_children' => 1,
        )
    )
);
$query = new WP_Query;
$allproducts = $query->query($args);

$allproductscount = count($allproducts);

foreach($allproducts as $allproduct){
    $product = new WC_product($allproduct->ID);
    
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
    
    $google_xml .= 
    "<item>
    <g:id>".$allproduct->ID."</g:id>";
    //Название и описание
    $google_xml .= "<g:title>".htmlspecialchars($allproduct->post_title)."</g:title>
    <g:description><![CDATA['".htmlspecialchars(strip_tags($allproduct->post_content))."]]></g:description>
    <g:link>".get_permalink($allproduct->ID)."</g:link>";

    // Получаем картинку товара. Если она у вас хранится в мета поле, берите из него.
    $product_img=wp_get_attachment_image_src(get_post_thumbnail_id($allproduct->ID),'full');  

    if(is_array($product_img) && $product_img[0])
    $google_xml .= "<g:image_link>".$product_img[0]."</g:image_link>";

    //дополнительные изображения товара
    $attachment_ids = $product->get_gallery_image_ids();

    foreach( $attachment_ids as $attachment_id ){
        // Display the image URL
        $google_xml .= "
        <g:additional_image_link>".wp_get_attachment_url( $attachment_id )."</g:additional_image_link>";
    };

    $google_xml .= "<g:condition>new</g:condition>";
    
    $stock_status = $product->get_stock_status();
    $stock_qty = $product->get_stock_quantity();
    date_default_timezone_set('Europe/Moscow');
    
    if(check_category($product) === $plants_cat_id) {
        $backorderdate = date( "d.m.Y H:i:s", strtotime('next wednesday +2 week') );
    } else {
        $backorderdate = date( "d.m.Y H:i:s", strtotime('+1 week') );
    }
    $backorderdate_formatted = date(DATE_ISO8601, strtotime($backorderdate));
    if ($stock_status == 'instock') {
        if($stock_qty > 0) {
            $google_xml .= "<g:availability>in_stock</g:availability>";
        } else {
            $google_xml .= "<g:availability>preorder</g:availability>";
            $google_xml .= "<g:availability_date>".$backorderdate_formatted."</g:availability_date>";
        }
    }
    if ($stock_status == 'outofstock') {
        $google_xml .= "<g:availability>out_of_stock</g:availability>";
    }
    if ($stock_status == 'onbackorder') {
        $google_xml .= "<g:availability>preorder</g:availability>";
        $google_xml .= "<g:availability_date>".$backorderdate_formatted."</g:availability_date>";
    }


    // Получаем цену товара
    $sale = get_post_meta($allproduct->ID, '_sale_price', true);
    if ($sale>0) {
        $google_xml .= "<g:sale_price>".number_format(get_post_meta($allproduct->ID,'_sale_price',true),2,'.','')." RUB</g:sale_price>
        <g:price>".number_format(get_post_meta($allproduct->ID,'_regular_price',true),2,'.','')." RUB</g:price>";
    } else {
        $google_xml .= "<g:price>".number_format(get_post_meta($allproduct->ID,'_price',true),2,'.','')." RUB</g:price>";
    };
    $google_xml .= "<g:brand>Plantis</g:brand>";

    

    //Параметры товара - поменять для гугла

    // $product_attributes = get_post_meta($allproduct->ID, '_product_attributes', true);
    // if(is_array($product_attributes)) {
    //     foreach ($product_attributes as $product_attribute) {
    //         if($product_attribute['is_visible']) {
    //             $param_name = wc_attribute_label( $product_attribute['name'] );
    //             if($product_attribute['is_taxonomy']) {
    //                 $attribute_values = get_the_terms( $allproduct->ID, $product_attribute['name']);
    //                 $values = [];
    //                 foreach ($attribute_values as $value) {
    //                     $values[] = $value->name;
    //                 };
    //                 $param_value = implode(',', $values);
    //             } else {
    //                 $param_value =  $product_attribute['value'];
    //             }
    //             $google_xml .= '<param name ="'.$param_name.'">'.$param_value.'</param>';
    //         };
    //     }
    // }

    //Закрыли тег item
    $google_xml .= "</item>
    ";
}
//Конец файла

$google_xml .= "</channel>
</rss>
";

$fp = fopen( ABSPATH . "/wp-content/google-xml/feed-google-0.xml", 'w' ); 
fwrite( $fp, $google_xml );
fclose( $fp );