<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Famille Normandeau-Desilets - Acceuil</title>
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <script src="assets/js/main.js"></script>
    <?php
    require_once 'classes/database/cl_PhotosDB.php';
    $dbSb = new photosBD();
    $photoSb = $dbSb->getSidebarPhoto();
    $fileSb = $photoSb->get_Filename();
    $pathSb = $photoSb->get_P_Path();
    $locSb = $pathSb . $fileSb;
    ?>
</head>
<body>
<div class="page">
    <!-- ==== START MASTHEAD ==== -->
    <header class="masthead" role="banner">
        <div class="masthead__dropdown">
            <button class="masthead__dropbtn">Gestion des photos</button>
            <div class="masthead__dropdown-content">
                <a class="masthead__item" href="addRepository.html">Ajout d'un répertoire</a>
                <a class="masthead__item" href="addPhotos.html">Ajout de photos</a>
            </div>
        </div>
        <input type="button" class="masthead__account" value="Mon compte">

        <h1 class="masthead__title masthead__title--index">Les Normandeau-Desilets</h1>
        <h2 class="masthead__sub-title">Une courte histoire de Nous</h2>

        <nav role="navigation">
            <ul class="c-nav-main">
                <li class="c-nav-main__item"><a class="c-nav-main__link" href1="#">Acceuil</a>
                </li>
                <li class="c-nav-main__item"><a class="c-nav-main__link" href="readings.html">Lectures</a>
                </li>
                <li class="c-nav-main__item"><a class="c-nav-main__link" href="geneology.html">Généalogie</a>
                </li>
                <li class="c-nav-main__item"><a class="c-nav-main__link" href="objects.html">Objets de famille</a>
                </li>
                <li class="c-nav-main__item"><a class="c-nav-main__link" href="family_photos.html">La famille en
                        photos</a>
                </li>
            </ul>
        </nav>
    </header>
    <!-- end masthead -->
    <!-- ==== START SIDEBAR ==== -->
    <article class="about">
        <img class="about__img-me" src="<?php echo($locSb); ?>" alt="Michel Desilets">
        <p class="about__text">En construisant ce site, j'ai voulu conserver l'histoire de ma famille et un peu
            celle
            de ceux avec qui j'ai partagé ma vie.</p>
        <img src="assets/img/home/TransparantSignature02.jpg" alt="" class="about__signature">
    </article>
    <!-- end sidebar -->
</div>

<!-- ==== START MAIN ==== -->
<main role="main">
    <section class="home">
        <script>getPhotos(5, 4)</script>
        <div class="home__photo"></div>
    </section>
</main>

<!-- end page -->
</body>
</html>
