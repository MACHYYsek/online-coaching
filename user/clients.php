<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
require "../middlewares/auth.php";
auth()->checkAccess("/user/clients.php");

?>

<!DOCTYPE html>
<html>
<?php echo renderHead() ?>
<body>

<?php require "../components/navbar.php"; ?>

<?php
$user = auth();
$clients = $user->getAllClients();
$requestedClients = $user->getAllRequestedClients();
?>

<div class="user-section h-100 pt-nav">
    <?php
    if((count($clients) + count($requestedClients)) == 0) {
        echo "";
    } else {
        echo "<h1 class='text-center'>Vaši klienti</h1>";
    }
    ?>

    <div class="<?php
    if((count($clients) + count($requestedClients)) == 0) {
        echo "no-trainers";
    } else {
        echo "users";
    }
    ?>">
        <?php
        if ((count($clients) + count($requestedClients)) == 0) {
            echo "<h4 class='text-center text-danger'>Bohužel zatím nemáte žádného klienta</h4>";
        }

        foreach ($clients as $client) {
            echo '
            <div class="card text-center" style="width: 18rem;">
                <img src="'. $client->getAvatarURL() .'" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">'. $client->getFullName() .'</h5>
                    <form action="../actions/remove-client.php" method="get">
                        <input type="hidden" name="client_id" value="'. $client->id .'">
                        <button class="btn btn-primary">Odebrat clienta</button>
                    </form>
                     <form action="../actions/show-client.php" method="get" class="mt-1">
                        <input type="hidden" name="client_id" value="'. $client->id .'">
                        <button class="btn btn-warning">Zobrazit údaje</button>
                    </form>
                </div>
            </div>
            ';
        }

        foreach ($requestedClients as $requestedClient) {
            echo '
            <div class="card text-center" style="width: 18rem;">
                <img src="'. $requestedClient->getAvatarURL() .'" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">'. $requestedClient->getFullName() .'</h5>
                    <form action="../actions/approve-client.php" method="get">
                        <input type="hidden" name="client_id" value="'. $requestedClient->id .'">
                        <button class="btn btn-primary">Příjmout clienta</button>
                    </form>
                    <form action="../actions/decline-client.php" method="get">
                        <input type="hidden" name="client_id" value="'. $requestedClient->id .'">
                        <button class="btn btn-danger mt-2">Odmítnout clienta</button>
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