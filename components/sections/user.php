<div class="user-section pt-nav">
    <h1 class="text-center">Klientská Sekce</h1>
    <div class="buttons" size="<?php echo auth()->hasTrainer() ? '2' : '3' ?>">
        <div class="card text-center" style="width: 18rem;" disabled="<?php echo !auth()->hasTrainer() ? 'true' : 'false' ?>">
            <img src="../assets/icons/message.svg" class="card-img-top">
            <div class="card-body">
                <h5 class="card-title">Chat</h5>
                <a <?php echo auth()->loadTrainer() != null ? 'href="./chat.php"' : '' ?> class="btn btn-primary" disabled="<?php echo auth()->loadTrainer() == null ? 'true' : 'false' ?>">Zobrazit</a>
            </div>
        </div>

        <div class="card text-center" style="width: 18rem;" disabled="<?php echo !auth()->hasTrainer() ? 'true' : 'false' ?>">
            <img src="../assets/icons/settings.svg" class="card-img-top">
            <div class="card-body">
                <h5 class="card-title">Nastavení</h5>
                <a <?php echo auth()->loadTrainer() != null ? 'href="./settings.php"' : '' ?> class="btn btn-primary" disabled="<?php echo auth()->loadTrainer() == null ? 'true' : 'false' ?>">Zobrazit</a>
            </div>
        </div>

        <?php
        if(!auth()->hasTrainer()) {
            echo '<div class="card text-center" style="width: 18rem;" disabled="'. (auth()->hasRequestedTrainer() ? 'true' : 'false') .'">
            <img src="../assets/icons/users.svg" class="card-img-top">
            <div class="card-body">
                <h5 class="card-title">Najít Trénera</h5>
                <a href="./find-trainer.php" class="btn btn-primary">Najít</a>
            </div>
        </div>';
        }
        ?>

    </div>

    <?php
    if(!auth()->hasTrainer() && auth()->hasRequestedTrainer()) {
        echo '<div class="alert alert-danger" role="alert">
              Poslali jste žádost o trénera, ale zatím jste nebyl přiřazen. Počkejte na schválení.
        </div>';
    }
    ?>
</div>