<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
require $_SERVER['DOCUMENT_ROOT'] . "/middlewares/auth-redirect.php";
?>

<!DOCTYPE html>
<html>
<?php echo renderHead() ?>
<body>

<?php
require "components/navbar.php";
?>


<div class="main-content pt-nav">

    <div class="container py-5 h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-12 col-lg-9 col-xl-7">
                <div class="card shadow-2-strong card-registration" style="border-radius: 15px;">
                    <div class="card-body p-4 p-md-5">
                        <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Přihlášení</h3>
                        <form action="./actions/login.php" method="post">

                            <div class="row">
                                <div class="col-md-12 mb-4">

                                    <div class="form-outline">
                                        <label class="form-label" for="username">Username</label>
                                        <input type="text" id="username" name="username" required
                                               class="form-control "/>
                                    </div>

                                </div>
                                <div class="col-md-12 mb-4">

                                    <div class="form-outline">
                                        <label class="form-label" for="password">Heslo</label>
                                        <input type="password" id="password" name="password" required
                                               class="form-control "/></div>

                                </div>
                            </div>

                            <div class="mt-4 pt-2">
                                <input class="btn btn-primary" type="submit" value="Přihlásit se"/>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "./components/footer.php"; ?>

</body>
</html>