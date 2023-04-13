<?php

use Core\Enums\AccountType;
use Core\Enums\FlashType;
use Core\Models\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/flash.php';

session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/middlewares/auth.php';
auth()->checkAccess("/actions/decline-client.php");

$user = auth();

if(!isset($_GET["client_id"])) {
    flashWithRedirect(FlashType::ERROR, "Neexistující klient", "/user/clients.php");
    return;
}

$clientId = $_GET["client_id"];
$client = User::find($clientId);
if(!$client) {
    flashWithRedirect(FlashType::ERROR, "Neexistující klient", "/user/clients.php");
    return;
}
$medoo = getMedoo();

$canAccept = $medoo->count("clients", [
    "AND" => [
        "client" => $client->id,
        "trainer" => $user->id,
        "approved" => 0,
    ]
]);

if($canAccept == 0) {
    flashWithRedirect(FlashType::ERROR, "Klient nemá žádný požadavek na trenéra", "/user/clients.php");
    return;
}

$medoo->delete("clients", [
    "AND" => [
        "client" => $client->id,
        "trainer" => $user->id,
    ]
]);

flashWithRedirect(FlashType::SUCCESS, "Žádost o trenéra byla zamítnuta", "/user/clients.php");