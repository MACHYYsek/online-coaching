<?php

use Medoo\Medoo;

$db_host = 'oliveruhlik';
$db_user = 'Oliver123';
$db_password = '';
$db_db = 'coaching';
$db_port = 3306;

$database = new Medoo([
    'type' => 'mysql',
    'host' => $db_host,
    'database' => $db_db,
    'username' => $db_user,
    'password' => $db_password,
]);


function getMedoo(): Medoo
{
    global $database;
    return $database;
}