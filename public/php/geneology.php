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
    <title>Famille Normandeau-Desilets - Photos Famille</title>
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/main.css">
</head>

<body>
<?php if (login_check($mysqli) == true) : ?>
    <div class="page">
        <!-- ==== START MASTHEAD ==== -->
        <header class="masthead" role="banner">
            <h1 class="masthead__title">Les Normandeau-Desilets</h1>
            <h2 class="masthead__sub-title">Une courte histoire de Nous</h2>

            <nav role="navigation">
                <ul class="c-nav-main">
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="../../index.php">Acceuil</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="readings.php">Lectures</a>
                    </li>
                    <li class="c-nav-main__item"><a class="c-nav-main__link"
                                                    href="#">Généalogie</a>
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
        <!-- end masthead -->

        <!-- ==== MAIN ==== -->
        <div role="main" class="legacy">
            <section class="legacy__left">
                <h3 class="legacy__title">Michel Desilets</h3>
                <ul class="legacy__items">
                    <li><a class="legacy__item"
                           href="../legacy/desilets/asc_book/index.html">Livre
                            d'ascendance</a></li>
                    <li><a class="legacy__item"
                           href="../legacy/desilets/desc_book/index.html">Livre de
                            descendance</a></li>
                    <li><a class="legacy__item"
                           href="../legacy/desilets/asc_tree/index.html">Arbre
                            d'ascendance</a></li>
                    <li><a class="legacy__item"
                           href="../legacy/desilets/fam_file/index.html">Fiche
                            familiale</a></li>
                    <li><a class="legacy__item"
                           href="legacy/desilets/pers_file/index.html">Fiche
                            individuelle</a></li>
                    <li><a class="legacy__item" href="../legacy/ArbreMde.pdf">Arbre
                            de Michel Desilets</a></li>
                </ul>
            </section>
            <section class="legacy__right">
                <h3 class="legacy__title">Chantal Normandeau</h3>
                <ul class="legacy__items">
                    <li><a class="legacy__item" href="#">Livre d'ascendance</a>
                    </li>
                    <li><a class="legacy__item" href="#">Livre de
                            descendance</a></li>
                    <li><a class="legacy__item" href="#">Arbre d'ascendance</a>
                    </li>
                    <li><a class="legacy__item" href="#">Fiche familiale</a>
                    </li>
                    <li><a class="legacy__item" href="#">Fiche individuelle</a>
                    </li>
                </ul>
            </section>
        </div>
    </div>
<?php else : ?>
    <p>
        <span class="error">Vous devez être connecté au site pour pouvoir
            voir le contenu de cette page.</span>
        Svp <a href="../../index.php">connectez-vous.</a>.
    </p>
<?php endif; ?>
</body>
</html>
