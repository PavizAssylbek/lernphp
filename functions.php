<?php

function get_user_by_email(string $email): array {
  $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "");
  $sql = "SELECT * FROM registration WHERE email=:email";
  $statement = $pdo->prepare($sql);
  $statement -> execute(["email" => $email]);
  $user = $statement->fetchAll(PDO::FETCH_ASSOC);
  return $user;
};

function checEmailInDB(string $email):bool {
  $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "");
  $sql = "SELECT * FROM registration WHERE email=:email";
  $params = [
    ':email'  => $email,
  ];
  $statement = $pdo->prepare($sql);
  $statement->execute($params);
  return $statement->fetchColumn();
};

function newUser($email, $password, $username, $job, $tel, $address, $vk, $telegram, $instagram) {
  $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "");
  $sql = "INSERT INTO registration (email, password, username, job, tel, address, vk, telegram, instagram) VALUES (:email, :password,  :username, :job, :tel, :address, :vk, :telegram, :instagram)";
  $statement = $pdo->prepare($sql);
  $statement->execute([
    "email" => $email,
    "password" => password_hash($password, PASSWORD_DEFAULT),
    'username' => $username,
    'job' => $job,
    'tel' => $tel,
    'address' => $address,
    'vk' => $vk,
    'telegram' => $telegram,
    'instagram' => $instagram
    ]);
}

function add_user(string $email, string $password) {
  $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "");
  $sql = "INSERT INTO registration (email, password) VALUES (:email, :password)";
  $statement = $pdo->prepare($sql);
  $statement->execute([
    "email" => $email,
    "password" => password_hash($password, PASSWORD_DEFAULT)
  ]);
};

function set_flash_message(string $key, string $message) {
  $_SESSION[$key] = "$message";
};

function display_flash_message(string $key): void {
  if(isset($_SESSION[$key])):
    echo "<div class=\"alert alert-{$key} text-dark\" role=\"alert\">";
    echo $_SESSION[$key];
    unset($_SESSION[$key]);
    echo "</div>";
  endif;
};

function redirect_to(string $path) {
  header("Location:$path");
  exit;
};

function get_all_users():array {
  $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "");
  $sql = 'SELECT * FROM registration';

  $statement = $pdo->prepare($sql);
  $statement->execute();
  return $statement->fetchAll();
}

function checkAdmin():bool {
  if(isset($_SESSION["login"])) {
    if($_SESSION["login"]['role'] == 1) {
      return true;
    } else {
      return false;
    }
  }
  return false;
}

function checkMe(): bool {
  if(isset($_SESSION["login"])){
    return $_SESSION["login"]["id"];
  }
  else {
    return false;
  }
      
}

function login(string $email, string $password): bool {
  $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "");
  $sql = "SELECT * FROM registration WHERE email=:email";
  $statement = $pdo->prepare($sql);
  $statement->execute(["email" => $email]);
  $user = $statement->fetchAll(PDO::FETCH_ASSOC);
  $user_password_hash = $user[0]['password'];
  $password_verify = password_verify($password, $user_password_hash);
  $_SESSION['login'] = $user[0];
  return $password_verify;
};