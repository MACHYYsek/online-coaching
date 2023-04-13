<?php

use Core\Models\User;

require $_SERVER['DOCUMENT_ROOT'] ."/utils/mysql.php";

$user = null;

if (isset($_SESSION["id"])) {
    $user = new User($_SESSION["id"]);
    $success = $user->load();
    if (!$success) {
        header("Location: /actions/logout.php");
    }
}

function auth(): User
{
    global $user;
    return $user;
}