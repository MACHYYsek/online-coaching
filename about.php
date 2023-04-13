<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/autoload.php';

session_start();
?>

<!DOCTYPE html>
<html>
<?php echo renderHead() ?>
<body>

<?php require "./components/navbar.php"; ?>


<div class="about-content pt-nav">
    <div class="container dots">

        <div class="row row-cols-1 row-cols-md-2 gy-3 mt-5">
            <div class="col">
                <img src="assets/img/img1.jpeg" alt="bg">
            </div>
            <div class="col">
                <h1 class="mb-4">Online spolupráce</h1>
                <p>Minimalistický, ale přesto naprosto komplexní přístup k tréninku, který zastáváme v našem soukromém
                    fitness studiu, jsme se rozhodli transformovat i do online spolupráce. Tato forma spolupráce je
                    vhodná především pro ty, kteří s naší pomocí chtějí dosáhnout výsledků a svých cílů, ale nemohou se
                    za námi dostavit osobně. Zároveň tuto variantu rádi nabídneme i klientům, u nichž nebude z
                    kapacitních důvodů možnost trénovat ve studiu. Máme pro vás připravené funkční a ověřené tréninkové
                    programy, které následně individuálně zacílíme konkrétním potřebám každého z vás. Potom jediné, co
                    vás bude od vysněného cíle dělit, bude už jen odhodlání začít a pustit se do toho s námi.</p>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 gy-3 mt-5">
            <div class="col">
                <h1 class="mb-4">Ušetři čas a peníze tím, že začneš jíst a cvičit správně již od začátku.</h1>
                <p>Vyhni se tak začátečnickým chybám. Nebudeš muset držet přehnané hladovky. Nebudeš muset trávit hodiny
                    na nudném rotopedu. Tím vším jsem si musel bohužel sám projít, než jsem pochopil, že tudy cesta
                    nevede.<br>

                    Sestavit správný dietní jídelníček a tréninkový plán není jednoduchá věc. Každý z nás je jiný, má
                    odlišné tělo, potřeby, vlastnosti i cíle. Nelze si najít obecný vzor a podle něho se řídit.<br>

                    Chce to dát si práci se získáním všech důležitých informací a teprve podle nich vytvořit rozumný
                    plán, který ti pomůže dostat se k vysněnému cíli. Využijeme mých zkušeností, vytvoříme základní
                    jídelníček nebo tréninkový plán, který ti bude vyhovovat a také tě bude bavit!<br>

                    Trénink i jídelníček budeme neustále vyvíjet k dokonalosti, abychom maximálně využili potenciál
                    tvého těla. Od této chvíle tě už nic nezastaví!

                </p>
            </div>
            <div class="col">
                <img src="assets/img/img2.jpeg" alt="bg">
            </div>
        </div>
    </div>
</div>
<div class="mt-5 text-center pb-5">
    <a class="btn btn-primary" href="/price.php" role="button">Zobrazit cenník</a>
</div>

<?php require "./components/footer.php"; ?>
<!-- Footer -->
</body>
</html>