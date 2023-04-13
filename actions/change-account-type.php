<?php

use Core\Enums\AccountType;
use Core\Enums\FlashType;
use Core\Models\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/middlewares/auth.php';
auth()->checkAccess("/actions/change-account-type.php");
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

$newAccountType = $user->getNextAccountType();
$oldAccountType = $user->accountType;

switch ($oldAccountType) {
    case AccountType::ADMIN:
        break;
    case AccountType::TRAINER:
        $medoo = getMedoo();
        $medoo->delete("clients", [
            "trainer" => $user->id
        ]);
        break;
    case AccountType::USER:
        $medoo = getMedoo();
        $medoo->delete("clients", [
            "client" => $user->id
        ]);
        break;
}

$user->update("accountType", $newAccountType);
$user->save();

flashWithRedirect(FlashType::SUCCESS, "Typ účtu byl změněn z $oldAccountType->name na $newAccountType->name", "/user/users.php");