<?php

use Core\Enums\AccountType;
use Core\Enums\FlashType;
use Core\Models\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
require "../middlewares/auth.php";
auth()->checkAccess("/user/find-trainer.php");

if(auth()->hasRequestedTrainer()) {
    flashWithRedirect(FlashType::ERROR, "Už jsi požádal o trenéra", "./index.php");
    return;
}

if(auth()->hasTrainer()) {
    flashWithRedirect(FlashType::ERROR, "Už jste si svého trénera vybrali", "./index.php");
    return;
}

?>

<!DOCTYPE html>
<html>
<?php echo renderHead() ?>
<body>

<?php require "../components/navbar.php"; ?>

<?php
$trainers = User::getAllTrainers();
?>

<div class="user-section h-100 pt-nav">
    <?php
    if(count($trainers) == 0) {
        echo "";
    } else {
        echo "<h1 class='text-center'>Vyberte si trenéra</h1>";
    }
    ?>

    <div class="<?php
    if(count($trainers) == 0) {
        echo "no-trainers";
    } else {
        echo "users";
    }
    ?>">
        <?php
        if (count($trainers) == 0) {
            echo "<h4 class='text-center text-danger'>Bohužel žádní trenéři nejsou aktuálne k dispozici</h4>";
        }

        foreach ($trainers as $trainer) {
            echo '
            <div class="card text-center" style="width: 18rem;">
                <img src="'. $trainer->getAvatarURL() .'" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">'. $trainer->getFullName() .'</h5>
                    <form action="../actions/request-trainer.php" method="post">
                        <input type="hidden" name="trainer_id" value="'. $trainer->id .'">
                        <button class="btn btn-primary">Odeslat žádost</button>
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