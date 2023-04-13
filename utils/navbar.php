<?php

function isActive($path, ...$paths)
{
    $currentPath = $_SERVER['REQUEST_URI'];
    if ($currentPath === $path) {
        return 'active';
    }

    if (in_array($currentPath, $paths, true)) {
        return 'active';
    }

    return "";
}