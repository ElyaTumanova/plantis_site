<?php
  $dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
  $root = $dom->createElement("yml_catalog"); // Создаём корневой элемент
  $root->setAttribute("date", date('Y-m-d H:i'));
  $dom->appendChild($root);
  $shop = $dom->createElement("shop");
  $root->appendChild($shop);

  //Добавляем элементы в shop
  $name = $dom->createElement("name", get_bloginfo('name'));
  $shop->appendChild($name);
  $company = $dom->createElement("company", get_bloginfo('name'));
  $shop->appendChild($company);
  $url = $dom->createElement("url", get_bloginfo('url'));
  $shop->appendChild($url);


//   $platform = $dom->createElement("platform",'WordPress - Yml for Yandex Market');
//   $version = $dom->createElement("version",'6.4.3');
//   $shop->appendChild($platform);
//   $shop->appendChild($version);

$currencies = $dom->createElement("currencies");
$shop->appendChild($currencies);
$currency = $dom->createElement("currency");
$currency->setAttribute("id", 'RUR');
$currency->setAttribute("rate", '1');
$currency->setAttribute("rate", '1');
$currencies->appendChild($currency); 

//Добавляем категории
$categories = $dom->createElement("categories");
$shop->appendChild($categories);

//список категорий
$args=array(
	'taxonomy'   => 'product_cat',
    'hide_empty' => true,
);
$cats=get_terms($args);

foreach($cats as $item) {
    $category = $dom->createElement("category",$item->name);
    $category->setAttribute("id", $item->term_id);
    if($item->parent!=0){
        $category->setAttribute("parentId", $item->parent);
	}
    $categories->appendChild($category);
}

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
  $dom->save("wp-content/yandex-xml/users1.xml"); // Сохраняем полученный XML-документ в файл
?>

