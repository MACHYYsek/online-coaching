<?php

use Core\Enums\FlashType;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/utils/flash.php';

session_start();
$_SESSION["id"] = null;
session_destroy();

session_start();
flashWithRedirect(FlashType::SUCCESS, "Úspěšně odhlášen", "/login.php");