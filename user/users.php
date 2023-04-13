<?php

use Core\Models\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
require "../middlewares/auth.php";
auth()->checkAccess("/user/users.php");

?>


<!DOCTYPE html>
<html>
<?php echo renderHead() ?>
<body>

<?php require "../components/navbar.php"; ?>

<?php
$user = auth();
$users = User::getAll();
?>

<div class="user-section h-100 pt-nav">
    <h1 class='text-center'>Uživatele</h1>

    <div class="users">
        <?php
        foreach ($users as $user) {
            echo '
            <div class="card text-center" style="width: 18rem;">
                <img src="'. $user->getAvatarURL() .'" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">'. $user->renderBadge(true) ." ". $user->getFullName() .'</h5>
                    <form action="../actions/change-account-type.php" method="get" class="mt-3">
                        <input type="hidden" name="user_id" value="'. $user->id .'">
                        <button class="btn btn-primary">Změnit na '. $user->getNextAccountType()->name .'(a)</button>
                    </form>
                    <form action="../actions/delete-user.php" method="get" class="mt-2">
                        <input type="hidden" name="user_id" value="'. $user->id .'">
                        <button class="btn btn-danger">Smazat uživatele</button>
                    </form> 
                </div>
            </div>
            ';
        }
        ?>
    </div>
</div>

<?php require "../components/footer.php"; ?>

</body>
</html>