<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
require "../middlewares/auth.php";
auth()->checkAccess("/user/settings.php");


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $_SESSION["client"] = $id;
    $clientInfo = auth()->getClientHealth($id);

} else {
    exit();
}
?>

<!DOCTYPE html>
<html>
<?php echo renderHead() ?>
<body>

<?php require "../components/navbar.php"; ?>

<div class="user-section h-100 pt-nav">
    <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Uživatel <?php echo $clientInfo['first_name'] . " " . $clientInfo['last_name']?></h3>
    <form action="../actions/settings.php" method="post" enctype="multipart/form-data">

        <div class="row">
            <div class="col-md-6 mb-4">

                <div class="form-outline">
                    <label class="form-label" for="weight">Váha</label>
                    <input type="number" id="weight" name="weight"
                           class="form-control " disabled value="<?php echo $clientInfo['weight'] ?>" />
                </div>

            </div>
            <div class="col-md-6 mb-4">

                <div class="form-outline">
                    <label class="form-label" for="height">Výška</label>
                    <input type="number" id="height" name="height"
                           class="form-control " disabled value="<?php echo $clientInfo['height'] ?>" />
                </div>

            </div>

            <div class="col-md-6 mb-4">

                <div class="form-outline">
                    <label class="form-label" for="pressure">Tlak</label>
                    <input type="number" id="pressure" name="pressure"
                           class="form-control " disabled value="<?php echo $clientInfo['pressure'] ?>" />
                </div>

            </div>

            <div class="col-md-6 mb-4">

                <div class="form-outline">
                    <label class="form-label" for="pulse">Tep</label>
                    <input type="number" id="pulse" name="pulse"
                           class="form-control " disabled value="<?php echo $clientInfo['pulse'] ?>" />
                </div>

            </div>
            <div class="col-md-6 mb-4">

                <div class="form-outline">
                    <label class="form-label" for="bmi">BMI skóre</label>
                    <input type="text" id="bmi" name="bmi"
                           class="form-control " disabled value="<?php echo $clientInfo['bmi'] ?>"/></div>

            </div>

            <div class="col-md-6 mb-4">

                <div class="form-outline">
                    <label class="form-label" for="fat">Procento tuku</label>
                    <input type="number" id="fat" name="fat"
                           class="form-control " disabled value="<?php echo $clientInfo['fat'] ?>"/></div>

            </div>

            <div class="col-md-6 mb-4">

                <div class="form-outline">
                    <label class="form-label" for="bio">Bio</label>
                    <input type="text" id="bio" name="bio"
                           class="form-control " disabled value="<?php echo $clientInfo['bio'] ?>"/></div>

            </div>
        </div>

        <div class="mt-4 pt-2 text-center">
            <a href="./clients.php"> <div class="btn btn-primary">Zpět na klienty</div></a>
        </div>

    </form>

</div>

<?php require "../components/footer.php"; ?>

</body>
</html>