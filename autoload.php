<?php
require $_SERVER['DOCUMENT_ROOT'] . '/components/header.php';
require $_SERVER['DOCUMENT_ROOT'] ."/utils/navbar.php";
require_once $_SERVER['DOCUMENT_ROOT'] .'/utils/flash.php';

spl_autoload_register(function($className) {
    $className = str_replace("\\", "/", $className);
    include_once $_SERVER['DOCUMENT_ROOT'] . '/' . $className . '.php';
});

require $_SERVER['DOCUMENT_ROOT'] ."/vendor/autoload.php";