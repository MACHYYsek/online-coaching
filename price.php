<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();

?>

<!DOCTYPE html>
<html>
<?php echo renderHead() ?>
<body>

<?php require "./components/navbar.php"; ?>

<div class="mt-5 pt-nav">
    <div class="container p-5">
        <div class="cenik">
            <div class="text-center">
                <h1 class="mb-4">Ceník</h1>
            </div>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 gy-3">
                <div class="col">
                    <div class="bg-dark text-light p-3 border border-white rounded">
                        <h2 class="text-light">Balíček BASIC</h2>
                        <ul>
                            <li>Jídelníček BASIC</li>
                            <li>Tréninkový plán BASIC</li>
                            <li>Online konzultace BASIC</li>
                            <li>2x Vstup do fitness zdarma</li>
                        </ul>
                        <h3 class="text-light">Cena: 4 000 Kč</h3>
                    </div>
                </div>
                <div class="col">
                    <div class="bg-dark text-light p-3 border border-white rounded">

                        <h2 class="text-light">Balíček OK</h2>
                        <ul>
                            <li>Jídelníček OK</li>
                            <li>Tréninkový plán OK</li>
                            <li>Online konzultace OK</li>
                            <li>4x Vstup do fitness zdarma</li>
                        </ul>
                        <h3 class="text-light">Cena: 6 000 Kč</h3>
                    </div>
                </div>
                <div class="col">
                    <div class="bg-dark text-light p-3 border border-white rounded">

                        <h2 class="text-light">Balíček PREMIUM</h2>
                        <ul>
                            <li>Jídelníček PREMIUM</li>
                            <li>Tréninkový plán PREMIUM</li>
                            <li>Online konzultace PREMIUM</li>
                            <li>Neomezený vstup do fitness zdarma</li>
                        </ul>
                        <h3 class="text-light">Cena: 10 000 Kč</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "./components/footer.php"; ?>

</body>
</html>