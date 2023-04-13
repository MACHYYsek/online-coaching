<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
require "../middlewares/auth.php";
auth()->checkAccess("/user/index.php");
?>

<!DOCTYPE html>
<html>
<?php echo renderHead() ?>
<body>

<?php require "../components/navbar.php"; ?>

<?php
$user = auth();
require "../components/sections/". strtolower($user->accountType->name) .".php";
?>

<?php require "../components/footer.php"; ?>

</body>
</html>