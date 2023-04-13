<?php

use Core\Models\User;

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
?>

<!DOCTYPE html>
<html>
<?php echo renderHead() ?>
<body>

<?php require "./components/navbar.php"; ?>

<div class="index-section mt-5">
    <div class="d-flex gap-4 justify-content-center align-items-center btn-container">
        <a href="./register.php" class="btn btn-primary">Registrace</a>
        <a href="./about.php" class="btn btn-secondary">O nás</a>
    </div>
</div>

<section class="testimonials text-center bg-light py-5">
    <div class="container">
        <h2 class="mb-5">Náš team</h2>
        <div class="row">
            <?php
            $trainers = User::getAllTrainers();

            foreach ($trainers as $trainer) {
                echo '
                <div class="col-lg-4">
                    <div class="mx-auto testimonial-item mb-5 mb-lg-0"><img class="avatar xl centered mb-3" src="'. $trainer->getAvatarURL() .'">
                        <h5>'. $trainer->getFullName() .'</h5>
                        <p class="font-weight-light mb-0">'. $trainer->bio .'</p>
                    </div>
                </div>
                ';
            }
            ?>
        </div>
    </div>
</section>

<?php require "./components/footer.php"; ?>
<!-- Footer -->
</body>
</html>