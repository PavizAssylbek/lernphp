<?php
session_start();
require "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];

$login_verify = login($email, $password);

if(empty($login_verify)) {
  set_flash_message("danger", "<strong>Уведомление!</strong> Неверный пароль или имя пользователя");
  redirect_to("page_login.php");
}
$_SESSION["email"] = $email;
set_flash_message("success", "Здравствуйте {$_SESSION["email"]}! Вы успешно авторизировались!");
redirect_to("users.php");

