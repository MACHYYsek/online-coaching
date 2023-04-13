<?php

use Core\Enums\AccountType;
use Core\Enums\FlashType;
use Core\Models\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/middlewares/auth.php';
auth()->checkAccess("/actions/delete-user.php");
$loggedUser = auth();

if(!isset($_GET["user_id"])) {
    flashWithRedirect(FlashType::ERROR, "Neexistující uživatel", "/user/users.php");
    return;
}

$userId = $_GET["user_id"];

$user = User::find($userId);
if(!$user) {
    flashWithRedirect(FlashType::ERROR, "Neexistující uživatel", "/user/users.php");
    return;
}

if($user->id == $loggedUser->id) {
    flashWithRedirect(FlashType::ERROR, "Nemůžete změnit typ svého účtu", "/user/users.php");
    return;
}

$medoo = getMedoo();
$medoo->delete("clients", [
    "OR" => [
        "client" => $user->id,
        "trainer" => $user->id
    ]
]);
$medoo->delete("messages", [
    "OR" => [
        "from" => $user->id,
        "to" => $user->id
    ]
]);

$user->delete();

flashWithRedirect(FlashType::SUCCESS, "Uživatel byl úspěšně smazán", "/user/users.php");