<?php

use Core\Enums\FlashType;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/middlewares/auth.php';

$user = auth();
$user->checkAccess("/actions/settings.php");

$weight = $_POST["weight"];
$height = $_POST["height"];
$pressure = $_POST["pressure"];
$pulse = $_POST["pulse"];
$bmi = $_POST["bmi"];
$fat = $_POST["fat"];
$bio = $_POST["bio"];

if (isset($weight) && $weight != "") {
    if(!preg_match("/^[0-9]{1,3}$/", $weight)) {
        flashWithRedirect(FlashType::ERROR, "Neplatná váha", "/user/settings.php");
        return;
    }

    if($weight < 0) {
        flashWithRedirect(FlashType::ERROR, "Neplatná váha", "/user/settings.php");
        return;
    }

    if($weight > 10000) {
        flashWithRedirect(FlashType::ERROR, "Neplatná váha", "/user/settings.php");
        return;
    }

    $user->update("weight", $weight);
}

if (isset($height) && $height != "") {
    if(!preg_match("/^[0-9]{1,3}$/", $height)) {
        flashWithRedirect(FlashType::ERROR, "Neplatná výška", "/user/settings.php");
        return;
    }

    if($height < 0) {
        flashWithRedirect(FlashType::ERROR, "Neplatná výška", "/user/settings.php");
        return;
    }

    if($height > 300) {
        flashWithRedirect(FlashType::ERROR, "Neplatná výška", "/user/settings.php");
        return;
    }

    $user->update("height", $height);
}

if (isset($pressure) && $pressure != "") {
    if(!preg_match("/^[0-9]{1,3}$/", $pressure)) {
        flashWithRedirect(FlashType::ERROR, "Neplatný tlak", "/user/settings.php");
        return;
    }

    if($pressure < 0) {
        flashWithRedirect(FlashType::ERROR, "Neplatný tlak", "/user/settings.php");
        return;
    }

    if($pressure > 300) {
        flashWithRedirect(FlashType::ERROR, "Neplatný tlak", "/user/settings.php");
        return;
    }

    $user->update("pressure", $pressure);
}

if (isset($pulse) && $pulse != "") {
    if(!preg_match("/^[0-9]{1,3}$/", $pulse)) {
        flashWithRedirect(FlashType::ERROR, "Neplatný tep", "/user/settings.php");
        return;
    }

    if($pulse < 0) {
        flashWithRedirect(FlashType::ERROR, "Neplatný tep", "/user/settings.php");
        return;
    }

    if($pulse > 300) {
        flashWithRedirect(FlashType::ERROR, "Neplatný tep", "/user/settings.php");
        return;
    }

    $user->update("pulse", $pulse);
}

if (isset($bmi) && $bmi != "") {
    if (!preg_match("/^[0-9]{1,3}$/", $bmi)) {
        flashWithRedirect(FlashType::ERROR, "Neplatný BMI", "/user/settings.php");
        return;
    }

    if($bmi < 0) {
        flashWithRedirect(FlashType::ERROR, "Neplatný BMI", "/user/settings.php");
        return;
    }

    if($bmi > 100) {
        flashWithRedirect(FlashType::ERROR, "Neplatný BMI", "/user/settings.php");
        return;
    }

    $user->update("bmi", $bmi);
}

if (isset($fat) && $fat != "") {
    if (!preg_match("/^[0-9]{1,3}$/", $fat)) {
        flashWithRedirect(FlashType::ERROR, "Neplatná tuková hmotnost", "/user/settings.php");
        return;
    }

    if($fat < 0) {
        flashWithRedirect(FlashType::ERROR, "Neplatný tuk", "/user/settings.php");
        return;
    }

    if($fat > 100) {
        flashWithRedirect(FlashType::ERROR, "Neplatný tuk", "/user/settings.php");
        return;
    }

    $user->update("fat", $fat);
}

if (isset($bio) && $bio != "") {
    if (strlen($bio) > 128) {
        flashWithRedirect(FlashType::ERROR, "Neplatný popis", "/user/settings.php");
        return;
    }

    if (strlen($bio) < 6) {
        flashWithRedirect(FlashType::ERROR, "Neplatný popis", "/user/settings.php");
        return;
    }

    $user->update("bio", $bio);
}

if(isset($_FILES["avatar"]) && $_FILES["avatar"]["name"] != "") {
    $avatar = $_FILES["avatar"];

    $ext = pathinfo(basename($avatar["name"]))['extension'];
    $allowedTypes = array("jpg", "jpeg", "png", "gif", "webp");
    if(!in_array($ext, $allowedTypes)) {
        flashWithRedirect(FlashType::ERROR, "Neplatný obrázek", "/user/settings.php");
        return;
    }

    $imageSize = $avatar['size'] / 1024;
    if($imageSize > 10240) {
        flashWithRedirect(FlashType::ERROR, "Obrázek je příliš velký", "/user/settings.php");
        return;
    }

    $imageName = md5(uniqid(rand(), true)) . "." . $ext;
    $target = $_SERVER['DOCUMENT_ROOT'] . "/storage/avatars/" . $imageName;
    move_uploaded_file($avatar["tmp_name"], $target);

    $user->update("avatar", $imageName);
}

$user->save();
flashWithRedirect(FlashType::SUCCESS, "Úspěšně uloženo", "/user/settings.php");