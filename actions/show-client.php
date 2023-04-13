<?php

use Core\Enums\AccountType;
use Core\Enums\FlashType;
use Core\Models\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/flash.php';

session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/middlewares/auth.php';
auth()->checkAccess("/actions/remove-client.php");

$user = auth();
$server = "http://localhost";


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

$canRemove = $medoo->count("clients", [
    "AND" => [
        "client" => $client->id,
        "trainer" => $user->id,
        "approved" => 1,
    ]
]);

if($canRemove == 0) {
    flashWithRedirect(FlashType::ERROR, "Tenhle klient nespadá pod vás", "/user/clients.php");
    return;
} else{
    header("Location: $server/user/client.php?id=$client->id");
}
