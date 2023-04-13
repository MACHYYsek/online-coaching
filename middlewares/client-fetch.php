<?php

use Core\Models\Client;

require $_SERVER['DOCUMENT_ROOT'] ."/utils/mysql.php";

$client = null;

if (isset($_SESSION["client"])) {
    $client = new Client($_SESSION["client"]);
    if (!$success) {
        header("Location: /actions/logout.php");
    }
}

function getClient(): Client
{
    global $client;
    return $client;
}