<?php

use Core\Enums\AccountType;
use Core\Enums\FlashType;
use Core\Models\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/flash.php';

session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/middlewares/auth.php';

$user = auth();
$user->checkAccess("/actions/request-trainer.php");

if ($user->hasTrainer()) {
    flashWithRedirect(FlashType::ERROR, "Už máš trenéra", "/user/index.php");
    return;
}

if($user->hasRequestedTrainer()) {
    flashWithRedirect(FlashType::ERROR, "Už jsi požádal o trenéra", "/user/index.php");
    return;
}

$trainerId = $_POST["trainer_id"];
if (!isset($trainerId)) {
    flashWithRedirect(FlashType::ERROR, "Neexistující trenér", "/user/index.php");
    return;
}

$trainer = User::find($trainerId);
if (!$trainer) {
    flashWithRedirect(FlashType::ERROR, "Neexistující trenér", "/user/index.php");
    return;
}

if ($trainer->accountType != AccountType::TRAINER) {
    flashWithRedirect(FlashType::ERROR, "Neexistující trenér", "/user/index.php");
    return;
}

$medoo = getMedoo();

$medoo->insert("clients", [
    "client" => $user->id,
    "trainer" => $trainer->id,
    "approved" => 0,
    "created_at" => date("Y-m-d H:i:s"),
]);

flashWithRedirect(FlashType::SUCCESS, "Požadavek na trenéra byl odeslán", "/user/index.php");