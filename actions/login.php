<?php

use Core\Enums\FlashType;

require "../middlewares/auth-redirect.php";

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/flash.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/mysql.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/hashing.php';

session_start();

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $usernameRegex = '/^[a-z0-9_-]{3,64}$/';
    if (preg_match($usernameRegex, $username) === 0) {
        flashWithRedirect(FlashType::ERROR, "Neplatné uživatelské jméno", "/login.php");
        return;
    }

    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})^/';
    if (preg_match($passwordPattern, $password) === 0) {
        flashWithRedirect(FlashType::ERROR, "Neplatné heslo", "/login.php");
        return;
    }

    $medoo = getMedoo();
    $data = $medoo->select("users", ["id", "password"], [
        "username" => $username,
    ]);

    if (count($data) === 0) {
        flashWithRedirect(FlashType::ERROR, "Špatné jméno / heslo", "/login.php");
        return;
    }
    $userPassword = $data[0]["password"];

    if (!password_verify($password, $userPassword)) {
        flashWithRedirect(FlashType::ERROR, "Špatné heslo", "/login.php");
        return;
    }

    $_SESSION["id"] = $data[0]["id"];
    flashWithRedirect(FlashType::SUCCESS, "Úspěšně přihlášen", "/user/index.php");
} else {
    flashWithRedirect(FlashType::ERROR, "Chyba formuláře", "/login.php");
    return;
}