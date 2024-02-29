<?php
  $dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
  $root = $dom->createElement("yml_catalog"); // Создаём корневой элемент
  $dom->appendChild($root);
  $shop = $dom->createElement("shop");
  $root->appendChild($shop);

  $name = $dom->createElement("name",'Plantis');
  $company = $dom->createElement("company",'Plantis');
  $url = $dom->createElement("url",'https://plantis.shop/');
  $platform = $dom->createElement("platform",'WordPress - Yml for Yandex Market');
  $version = $dom->createElement("version",'6.4.3');

  $shop->appendChild($name);
  $shop->appendChild($company);
  $shop->appendChild($url);
  $shop->appendChild($platform);
  $shop->appendChild($version);
  


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


<!-- 
<name>Plantis</name>
<company>Plantis</company>
<url>https://plantis.shop/</url>
<platform>WordPress - Yml for Yandex Market</platform>
<version>6.4.3</version> -->