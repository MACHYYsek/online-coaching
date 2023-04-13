<?php
if (isset($_SESSION["id"])) {
    header("Location: ./user/index.php");
}