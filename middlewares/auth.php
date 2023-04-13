<?php
if (!isset($_SESSION["id"])) {
    header("Location: ./login.php");
}

require_once $_SERVER['DOCUMENT_ROOT'] ."/middlewares/auth-fetch.php";