<?php
include_once '../../private/initialize.php';
include_once CONNECTION_PATH . '/connect.php';
include_once '../../includes/functions.php';

sec_session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Famille Normandeau-Desilets - Lectures</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/main.css">
    <script src="../js/main.js"></script>
</head>
<body>
<?php if (login_check($mysqli) == true) : ?>
<div class="page">
    <!-- ==== START MASTHEAD ==== -->
    <div>
        <header class="masthead" role="banner">
            <h1 class="masthead__title">Les Normandeau-Desilets</h1>
            <h2 class="masthead__sub-title">Une courte histoire de Nous</h2>

            <nav role="navigation">
                <ul class="c-nav-main">
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="../../index.php">Acceuil</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="">Lectures</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="geneology.php">Généalogie</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="objects.php">Objets de
                            famille</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="familyPhotos.php">La
                            famille en
                            photos</a>
                    </li>
                </ul>
            </nav>
        </header>
    </div>
    <!-- end masthead -->
    <main role=main class="menu1">
        <ul>
            <li class="menu1__item menu1__item--left">
            </li>
            <li class="menu1__item menu1__item--right" onclick="getReadings()">
            </li>
        </ul>
        <!--            <p>
                        Cette page contient un ensemble de documents (livres,lettres personnelles, etc.) que vous trouverez
                        surement intéressants.
                    </p>-->
        <section class="readings"></section>
    </main>
    <script>assignReadingTitle()</script>
    <script>getReadings()</script>

    <?php else : ?>
    <p>
        <span class="error">Vous devez être connecté au site pour pouvoir
            voir le contenu de cette page.</span>
        Svp <a href="../../index.php">connectez-vous.</a>.
    </p>
    <?php endif; ?>
</body>
</html>
