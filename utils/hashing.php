<?php

$options = [
    'cost' => 12,
];

function hashPassword($password): string
{
    global $options;
    return password_hash($password, PASSWORD_BCRYPT, $options);
}