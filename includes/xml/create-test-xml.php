<?php
  $dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
  $root = $dom->createElement("users"); // Создаём корневой элемент
  $dom->appendChild($root);
  $logins = array("User1", "User2", "User3"); // Логины пользователей
  $passwords = array("Pass1", "Pass2", "Pass3"); // Пароли пользователей
  for ($i = 0; $i < count($logins); $i++) {
    $id = $i + 1; // id-пользователя
    $user = $dom->createElement("user"); // Создаём узел "user"
    $user->setAttribute("id", $id); // Устанавливаем атрибут "id" у узла "user"
    $login = $dom->createElement("login", $logins[$i]); // Создаём узел "login" с текстом внутри
    $password = $dom->createElement("password", $passwords[$i]); // Создаём узел "password" с текстом внутри
    $user->appendChild($login); // Добавляем в узел "user" узел "login"
    $user->appendChild($password);// Добавляем в узел "user" узел "password"
    $root->appendChild($user); // Добавляем в корневой узел "users" узел "user"
  }
  $dom->save("users.xml"); // Сохраняем полученный XML-документ в файл
?>