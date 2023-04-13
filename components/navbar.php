<?php

require_once $_SERVER["DOCUMENT_ROOT"] ."/middlewares/auth-fetch.php";
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top border-0">
    <div class="container bg-light border-0">
        <a class="navbar-brand" href="/">OnlineCoaching</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler"
                aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav me-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive("/", "/index.php") ?>" href="/index.php">Úvod</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive("/about.php") ?>" href="/about.php">O nás</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo isActive("/price.php") ?>" href="/price.php">Ceník</a>
                </li>
            </ul>
            <div>
                <?php
                if (isset($_SESSION["id"])) {
                    echo '<div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle user-info" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        '. auth()->renderBadge() .'
                        
                        <img src="' . auth()->getAvatarURL() . '">
                        ' . auth()->username . '
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/user/index.php">Účet</a>
                        <a class="dropdown-item" href="/actions/logout.php">Odhlásit se</a>
                    </div>
                </div>';
                } else {
                    echo '<ul class="navbar-nav me-auto mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link '. isActive("/register.php") .'" href="/register.php">Registrace</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link '. isActive("/login.php") .'" href="/login.php">Přihlášení</a>
                </li>
            </ul>';
                }
                ?>
            </div>
        </div>
    </div>
</nav>