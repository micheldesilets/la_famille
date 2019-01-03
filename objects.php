<?php
include_once 'private/initialize.php';
include_once CONNECTION_PATH . '/connect.php';
include_once 'includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Famille Normandeau-Desilets - Objets de famille</title>
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <script src="assets/js/main.js"></script>
</head>
<body id="bdy">
<?php if (login_check($mysqli) == true) : ?>
<div class="page">
    <!-- ==== START MASTHEAD ==== -->
    <div class="header-cont">
        <header class="masthead" role="banner" id="myHeader">
            <h1 class="masthead__title">Les Normandeau-Desilets</h1>
            <h2 class="masthead__sub-title">Une courte histoire de Nous</h2>

            <nav role="navigation">
                <ul class="c-nav-main">
                    <li class="c-nav-main__item"><a class="c-nav-main__link" href="index.php">Acceuil</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link" href="readings.php">Lectures</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link" href="geneology.php">Généalogie</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link" href="">Objets de famille</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link" href="familyPhotos.php">La famille en
                            photos</a>
                    </li>
                </ul>
            </nav>
        </header>
    </div>
    <!-- end masthead -->


    <main role=main class="objects">
        <div>
            <section class="objects__container">
                <div></div>
            </section>
        </div>

        <!-- The Modal -->
        <div class="objects__modal photos__modal">
            <span class="close">x</span>
            <img class="objects__modal-content">
        </div>
    </main>

</div>
<script>getObjects()</script>

<?php else : ?>
<p>
            <span class="error">Vous devez être connecté au site pour pouvoir
            voir le contenu de cette page.</span>
    Svp <a href="index.php">connectez-vous.</a>.
</p>
<?php endif; ?>
</body>
</html>
