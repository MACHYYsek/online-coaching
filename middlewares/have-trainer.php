<?php
require_once $_SERVER['DOCUMENT_ROOT'] ."/middlewares/auth-fetch.php";

$user = auth();
if (!$user->hasTrainer()) {
    header("Location: ./index.php");
}