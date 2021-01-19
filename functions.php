<?php

function get_user_by_email(string $email) {
    $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "");
    $sql = "SELECT * FROM registration WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement -> execute(["email" => $email]);
    $user = $statement->fetchAll(PDO::FETCH_ASSOC);
    return $user;
};

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

function display_flash_message(string $key) {
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

function login(string $email, string $password): bool {
    $pdo = new PDO("mysql:host=localhost;dbname=login", "root", "");
    $sql = "SELECT * FROM registration WHERE email=:email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetchAll(PDO::FETCH_ASSOC);
    $user_password_hash = $user[0]['password'];
    $password_verify = password_verify($password, $user_password_hash);
    return $password_verify;
};