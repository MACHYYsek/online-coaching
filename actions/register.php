<?php

use Core\Enums\FlashType;
use Core\Enums\AccountType;

require "../middlewares/auth-redirect.php";

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/flash.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/mysql.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/hashing.php';

session_start();

if (
    isset($_POST["first_name"]) &&
    isset($_POST["last_name"]) &&
    isset($_POST["username"]) &&
    isset($_POST["email"]) &&
    isset($_POST["password"]) &&
    isset($_POST["password_confirm"])
) {
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordConfirm = $_POST["password_confirm"];
    $trainer = isset($_POST["trainer"]);

    if (!preg_match("/^[a-zA-ZčřžýáíéúůúšťďňěĚŠČŘŽÝÁÍÉÚŮŤŇ]{2,64}$/", $firstName)) {
        flashWithRedirect(FlashType::ERROR, "Neplatné jméno", "/register.php");
        return;
    }

    if (!preg_match("/^[a-zA-ZčřžýáíéúůúšťďňěĚŠČŘŽÝÁÍÉÚŮŤŇ]{2,64}$/", $lastName)) {
        flashWithRedirect(FlashType::ERROR, "Neplatné příjmení", "/register.php");
        return;
    }

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        flashWithRedirect(FlashType::ERROR, "Neplatný email", "/register.php");
        return;
    }

    $usernameRegex = '/^[a-z0-9_-]{3,64}$/';
    if (preg_match($usernameRegex, $username) === 0) {
        flashWithRedirect(FlashType::ERROR, "Neplatné uživatelské jméno", "/register.php");
        return;
    }

    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})^/';
    if (preg_match($passwordPattern, $password) === 0) {
        flashWithRedirect(FlashType::ERROR, "Neplatné heslo", "/register.php");
        return;
    }

    if ($password !== $passwordConfirm) {
        flashWithRedirect(FlashType::ERROR, "Hesla se neshodují", "/register.php");
        return;
    }

    $password = hashPassword($password);
    $accountType = AccountType::USER->name;

    if ($trainer) {
        $accountType = AccountType::TRAINER->name;
    }

    $medoo = getMedoo();
    $data = $medoo->select("users", ["id"], [
        "OR" => [
            "username" => $username,
            "email" => $email
        ]
    ]);

    if (count($data) > 0) {
        flashWithRedirect(FlashType::ERROR, "Uživatel s tímto uživatelským jménem nebo emailem již existuje", "/register.php");
        return;
    }

     $medoo->insert("users", [
        "first_name" => $firstName,
        "last_name" => $lastName,
        "username" => $username,
        "email" => $email,
        "password" => $password,
        "account_type" => $accountType,
         "bio" => null,
    ]);

    $_SESSION["id"] = $medoo->id();

    flashWithRedirect(FlashType::SUCCESS, "Úspěšně zaregistrován", "/user/index.php");
} else {
    flashWithRedirect(FlashType::ERROR, "Chyba formuláře", "/register.php");
    return;
}